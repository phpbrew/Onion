<?php
/*
 * This file is part of the {{ }} package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Onion;
use SimpleXMLElement;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Exception;
use SplFileInfo;
use Onion\ConfigContainer;
use Onion\SpecUtils;

class PackageConfigReader
{
    public $file;
    public $config;
    public $context;

    function __construct( \CLIFramework\CommandContext $cx, $file = 'package.ini')
    {
        $this->context = $cx;
        $this->file = $file;
        if( ! file_exists($file) ) {
            $cx->logger->error( "$file not found." );
            exit(1);
        }

        try {
            $this->config = new ConfigContainer( parse_ini_file( $this->file , true ) );
        } 
        catch ( Exception $e ) {
            $cx->logger->error( "$file syntax error." );
            $cx->logger->error( $e->getMessage() );
            exit(1);
        }
    }

    /* read package.ini as package.xml, to make the config package.xml 2.0 
     * compatible */
    function readAsPackageXml()
    {
        // prepare config data
        $config = & $this->config;
        $cx = $this->context;


        /* check required attributes */
        if( ! $config->has('package.name') ) {
            $cx->logger->error('package.name is not defined.');
            # echo "\n\n";
            # echo "\t[package]\n";
            # echo "\tname = {your package name}\n\n";
            exit(1);
        }

        if( ! $config->has('package.desc') ) {
            $cx->logger->error('package.desc is not defined.');
            exit(1);
        }

        if( ! $config->has('package.version') ) {
            $cx->logger->error('package.version is not defined.');
            exit(1);
        }

        if( ! $config->has('package.author') && ! $config->has('package.authors') ) {
            $cx->logger->error('package author or authors is not defined.');

            echo "Attribute 'author' or 'authors' is not defined.\n";
            echo "Please define 'author' in your package.ini file: \n\n";
            echo "[package]\n";
            echo "author = Name \"username\" <email@domain.com>\n\n";
            exit(1);
        }



        /* check optional attributes */

        if( ! $config->has('package.summary') ) {
            $descs = explode("\n",$config->get('package.desc'));
            $config->set('package.summary',$descs[0]);  # use first line desc as summary by default.
        }

        if( ! $config->has('package.license') ) {
            $cx->logger->info("* license is not defined., use PHP license by default.",1);
            $config->set('package.license','PHP LICENSE');
        }


        // XXX: check authors[] config


        /* XXX: support license section
            *
            * <license uri="http://www.opensource.org/licenses/bsd-license.php">BSD Style</license>
            */

        if( ! $config->has('package.channel' ) ) {
            $cx->logger->info("* package channel is not defined. use pear.php.net by default.",1);
            $config->set('package.channel','pear.php.net');
        }

        if( ! $config->has('package.date') ) {
            $date = date('Y-m-d');
            $cx->logger->info("* package date is not defined. use current date $date by default.",1);
            $config->set('package.date',$date);
        }

        if( ! $config->has('package.time') ) {
            $time = strftime('%X');
            $cx->logger->info("* package time is not defined. use current time $time by default.",1);
            $config->set('package.time',strftime('%X'));
        }

        if( ! $config->has('package.notes') ) {
            $config->set('package.notes','-');
        }

        // apply api_version from 'version', if not specified.
        if( ! $config->has('package.version-api') ) {
            $config->set('package.version-api',$config->get('package.version'));
        }

        if( $config->has('package.stability') ) {
            $s = $config->get('package.stability');
            $config->set('package.stability-release', $s );
            $config->set('package.stability-api', $s );
        }

        if( ! $config->has('package.stability') &&
            ! $config->has('package.stability-release') &&
            ! $config->has('package.stability-api') ) {
            $cx->logger->info("* package.stability is not set, use alpha by default",1);
            $config->set('package.stability-release', 'alpha' );
            $config->set('package.stability-api', 'alpha' );
        }

        /* XXX: check stability valid keywords */


        /* checking dependencies */
        $cx->logger->info2("Configuring dependencies...");
        if( ! $config->has('required') )  {
            $cx->logger->info("* required section is not defined. use php 5.3 and pearinstaller 1.4 by default.",1);
            $config->required = array( 
                'php' => '5.3',
                'pearinstaller' => '1.4',
            );
        }

        /* default role configs */
        $default_roles = array(
            'src' => 'php',
            'tests' => 'test',
            'data'  => 'data',
            'examples' => 'doc',
            'README.*' => 'doc',
            'bin'      => 'script',
        );
        if( ! $config->has('roles') ) {
            $config->roles = $default_roles;
        } else {
            $config->roles = array_merge($default_roles,$config->roles);
        }
    }


    function buildDependencyItem($section,$depinfo)
    {

        switch($depinfo['type']) {

        case 'package.uri':
            $pkg = $section->addChild('package');
            $pkg->name = $depinfo['name'];
            $pkg->uri  = $depinfo['uri'];
            break;

        case 'package':
            $pkg = $section->addChild('package');
            $pkg->name    = $depinfo['name'];
            $pkg->channel = $depinfo['channel'];
            if( isset($depinfo['version']['min']) )
                $pkg->min     = $depinfo['version']['min'];
            if( isset($depinfo['version']['max']) )
                $pkg->min     = $depinfo['version']['max'];
            break;


        case 'package.conflicts':
            $pkg = $section->addChild('package');
            $pkg->name = $depinfo['name'];
            $pkg->addChild('conflicts');
            break;

        case 'package.vcs':
            throw new Exception('package vcs is not supported currently.');
            break;

        }
    }

    function buildDependencySection($sectionName,$xml,$config)
    {
        $section = $xml->addChild($sectionName);
        foreach( $config->get($sectionName) as $key => $value ) 
        {
            $type = SpecUtils::detectDependency( $key , $value );
            switch($type) {


            case 'core':
                $version = SpecUtils::parseVersion( $value );
                $el = $section->addChild( $key );
                if( isset( $version['min'] ) )
                    $el->addChild( 'min' , $version['min'] );
                if( isset( $version['max'] ) )
                    $el->addChild( 'max' , $version['max'] );
                break;

            case 'extension':
                $depinfo = SpecUtils::parseDependency($key,$value);
                $el = $section->addChild('extension');
                $el->name = $depinfo['name'];
                if( isset($depinfo['version']['min'] ))
                    $el->min = $depinfo['version']['min'];
                if( isset($depinfo['version']['max'] ))
                    $el->max = $depinfo['version']['max'];
                break;

            case 'package':
                $depinfo = SpecUtils::parseDependency($key,$value);
                $this->buildDependencyItem($section,$depinfo);
                break;
            }
        }
    }

    function addFileNode($dir,$fileinfo,$role,$baseDir = null)
    {
        $cx = $this->context;

        # substr( $path->__tostring()  );
        $filepath = $fileinfo->getPathname();
        $md5sum   = md5_file($filepath);

        $target_filepath = $filepath;
        if( $baseDir )
            $target_filepath = substr( $filepath , strlen($baseDir) + 1 );

        $cx->logger->debug( sprintf('%s  %-5s  %s', 
            substr($md5sum,0,6),
            $role,
            $target_filepath
        ),1);
        $newfile = $dir->addChild('file');
        $newfile->addAttribute( 'install-as' , $target_filepath );
        $newfile->addAttribute( 'name'       , $filepath );
        $newfile->addAttribute( 'role'       , $role );
        $newfile->addAttribute( 'md5sum'     , $md5sum );
    }

    function generatePackageXml()
    {
        $cx = $this->context;
        // build pear config file.
        $config = $this->config;

        try {


            $xmlstr =<<<XML
            <package packagerversion="1.4.10" version="2.0"
            xmlns="http://pear.php.net/dtd/package-2.0"
            xmlns:tasks="http://pear.php.net/dtd/tasks-1.0"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
                                http://pear.php.net/dtd/tasks-1.0.xsd
                                http://pear.php.net/dtd/package-2.0
                                http://pear.php.net/dtd/package-2.0.xsd">
            </package>
XML;

            $xml              = new SimpleXMLElement($xmlstr); 
            $xml->name        = $config->{ 'package.name' };
            $xml->channel     = $config->{ 'package.channel' };

            if( $config->has('package.extends') )
                $xml->extends = $config->get('package.extends');

            $xml->summary     = $config->{ 'package.summary' };
            $xml->description = $config->{ 'package.desc' };


            $author_data = SpecUtils::parseAuthor( $config->get('package.author') );
            $lead = $xml->addChild('lead');
            foreach( $author_data as $k => $v )
                $lead->$k = $v;
            $lead->active = true;

            if( $config->has('package.authors') ) {
                foreach( $config->get('package.authors') as $author ) {
                    $lead = $xml->addChild('lead');
                    $data =  SpecUtils::parseAuthor( $author );
                    foreach( $data as $k => $v )
                        $lead->$k = $v;
                    $lead->active = 1;
                }
            }

            $xml->date        = $config->get('package.date');
            $xml->time        = $config->get('package.time');

            $version          = $xml->addChild('version');
            $version->release = $config->get('package.version');
            $version->api     = $config->get('package.version-api');

            $stability        = $xml->addChild('stability');
            $stability->release = $config->get('package.stability-release');  # XXX: detect from version number.
            $stability->api     = $config->get('package.stability-api');

            $xml->license     = $config->get('package.license');
            $xml->notes       = $config->get('package.notes');



            # <contents>
            #   <dir name="/">
            #     <file install-as="Twig/Autoloader.php" md5sum="b60338d1df4f145c7318d8f870925d1e" 
            #			name="lib/Twig/Autoloader.php" role="php" />
            #	</div>
            #
            #
            # <file md5sum="20075d1017c3c5f597e16a017e37e499" name="AUTHORS" role="doc" />
            # <file md5sum="f6426a3477833bdc3729e1ae9ee9c049" name="LICENSE" role="doc" />
            # <file md5sum="d07098d9bc4ffc2817419b2436a8ca6e" name="README.markdown" role="doc" />
            # </contents>




            // build contents section, TODO: support [roles] section.
            $cx->logger->info('Building contents section...');
            $contentsXml = $xml->addChild('contents');
            $dir = $contentsXml->addChild('dir');
            $dir->addAttribute('name','/');

            foreach( $config->roles as $path => $role )
            {

                if( is_dir($path) ) {
                    $baseDir = $path;
                    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir),
                                            RecursiveIteratorIterator::CHILD_FIRST);
                    foreach( $iterator as $path ) {
                        if( $path->isFile() ) {
                            $this->addFileNode($dir,$path,$role,$baseDir);
                        }
                    }
                }
                else {

                    $files = glob($path);
                    foreach( $files as $filename ) {
                        $fileinfo = new SplFileInfo($filename);
                        $this->addFileNode($dir,$fileinfo,$role);
                        # $file = $dir->addChild('file');
                        # $file->addAttribute( 'name' , $filename );
                        # $file->addAttribute( 'role' , $role );
                    }
                }
            }


            /*
            <dependencies>
                <required>
                    <php>
                        <min>5.2.4</min>
                    </php>
                    <pearinstaller>
                        <min>1.4.0</min>
                    </pearinstaller>
                    <extension>
                        <name>dom</name>
                    </extension>
                    <package>
                        <name>File_Iterator</name>
                        <channel>pear.phpunit.de</channel>
                        <min>1.3.0</min>
                    </package>
                </required>
            </dependencies>
            */

            $cx->logger->info('Building dependencies section...');

            $deps = $xml->addChild('dependencies');
            $this->buildDependencySection('required',$deps,$config);

            if( $config->has('optional') )
                $this->buildDependencySection('optional',$deps,$config);


            # TODO: support phprelease tag.
            $xml->addChild('phprelease');


            // use DOMDocument to reformat package.xml
            if( class_exists('DOMDocument') ) {
                $cx->logger->info('Re-formating XML...');
                $dom = new \DOMDocument('1.0');
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->loadXML($xml->asXML());
                return $dom->saveXML();
            }
            return $xml->asXML();
        } 
        catch (Exception $e) 
        {
            die( $e->getMessage() );
        } 
    }

}

