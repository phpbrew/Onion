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
use Onion\Package\Package;

/**
 *
 * package ini file reader:
 *
 *      $reader = new PackageIniReader();
 *      $reader->setLogger( $logger );
 *      $pkginfo = $reader->read( 'file.ini' );
 *
 *      $pkginfo->get( 'config' );
 *      $pkginfo->get( 'config.name' );
 *      $pkginfo->get( 'section.name' );
 *
 *      $pkgxml = new PackageXmlGenerator( $pkginfo );
 *      $pkgxml->setLogger( $logger );
 *      $pkgxml->useDefaultOptions(true);
 *      $pkgxml->setReformat(true);
 *      $pkgxml->generate('package.xml');
 *
 */
class PackageConfigReader 
    implements LoggableInterface 
{
    public $logger;

    function __construct()
    {
    }

    function setLogger( \CLIFramework\Logger $logger)
    {
        $this->logger = $logger;
    }

    function getLogger()
    {
        return $this->logger;
    }

    function __call($name,$arguments)
    {
        if( $this->logger )
            call_user_func_array( array($this->logger,$name) , $arguments );
    }

    function read($file)
    {
        $logger = $this->getLogger();
        $ini = null;
        try {
            $ini = parse_ini_file( $file , true );
        }
        catch( Exception $e ) {
            throw new Exception( "Package.ini: $file syntax error: " . $e->getMessage() );
        }

        if( ! $ini )
            throw new Exception( "$file is empty." );

        $config = new ConfigContainer( $ini );

        // preprocess, validate sections only for package.ini
        $pkginfo = new Package;
        $pkginfo->config = $config;

        // validation 
        $requiredFields = explode(' ','package.name package.desc package.version');
        foreach( $requiredFields as $f ) {
            if( ! $config->has( $f ) )
                throw new \Onion\Exception\InvalidConfigException( "$f is not defined." );
        }

        if( ! $config->has('package.authors') && ! $config->has('package.author') ) {
            echo <<<EOT
Attribute 'author' or 'authors' is not defined.
Please define 'author' in your package.ini file:

[package]
author = Name <email@domain.com>
EOT;
            throw new \Onion\Exception\InvalidConfigException('package.author or package.authors is not defined.');
        }


        // set default values
        if( ! $config->has('package.summary') ) {
            $logger->debug("* summary is not defined., use the first paragraph from description by default.",1);
            $descs = explode("\n",$config->get('package.desc'));
            $config->set('package.summary',$descs[0]);  # use first line desc as summary by default.
        }

        if( ! $config->has('package.license') ) {
            $logger->debug("* license is not defined., use PHP license by default.",1);
            $config->set('package.license','PHP');
        }

        // build package meta info
        $pkginfo->name = $config->get('package.name');
        $pkginfo->desc = $config->get('package.desc');
        $pkginfo->summary = $config->get('package.summary');
        $pkginfo->version = $config->get('package.version');
        $pkginfo->stability = $config->get('package.stability');

        $pkginfo->apiVersion = $config->get('package.version.api');
        $pkginfo->apiStability = $config->get('package.stability.api');
        $pkginfo->releaseStability = $config->get('package.stability.release');
        $pkginfo->license = $config->get('package.license');
        $pkginfo->licenseUri = $config->get('package.license.uri');


        // read dependency sections

        // checking dependencies
        $logger->info("Configuring dependencies...");
        if( ! $config->has('require') ) {
            $logger->info2("* required section is not defined. use php 5.3 and pearinstaller 1.4 by default.",1);
            $config->require = array(
                'php' => '5.3',
                'pearinstaller' => '1.4',
            );
        }

        return $pkginfo;
    }
}

class PackageConfigReader2
{

    /* read package.ini as package.xml, to make the config package.xml 2.0 
     * compatible */
    function readAsPackageXml()
    {
        /* XXX: support license section
            *
            * <license uri="http://www.opensource.org/licenses/bsd-license.php">BSD Style</license>
            */

        /**
         * package xml must have some default value
         */
        if( ! $config->has('package.channel' ) ) {
            $logger->info2("* package channel is not defined. use pear.php.net by default.",1);
            $config->set('package.channel','pear.php.net');
        }

        if( ! $config->has('package.date') ) {
            $date = date('Y-m-d');
            $logger->info2("* package date is not defined. use current date $date by default.",1);
            $config->set('package.date',$date);
        }

        if( ! $config->has('package.time') ) {
            $time = strftime('%X');
            $logger->info2("* package time is not defined. use current time $time by default.",1);
            $config->set('package.time',strftime('%X'));
        }

        if( ! $config->has('package.notes') ) {
            $config->set('package.notes','-');
        }

        // apply api_version from 'version', if not specified.
        if( ! $config->has('package.version.api') ) {
            $config->set('package.version-api',$config->get('package.version'));
        }

        if( $config->has('package.stability') ) {
            $s = $config->get('package.stability');
            $config->set('package.stability.release', $s );
            $config->set('package.stability.api', $s );
        }

        if( ! $config->has('package.stability') &&
            ! $config->has('package.stability.release') &&
            ! $config->has('package.stability.api') ) {
            $logger->info2("* package.stability is not set, use alpha by default",1);
            $config->set('package.stability.release', 'alpha' );
            $config->set('package.stability.api', 'alpha' );
        }

        /* XXX: check stability valid keywords */


        /* checking dependencies */
        $logger->info("Configuring dependencies...");
        if( ! $config->has('required') )  {
            $logger->info2("* required section is not defined. use php 5.3 and pearinstaller 1.4 by default.",1);
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
            'doc'      => 'doc',
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
        # substr( $path->__tostring()  );
        $filepath = $fileinfo->getPathname();
        $md5sum   = md5_file($filepath);

        $target_filepath = $filepath;
        if( $baseDir )
            $target_filepath = substr( $filepath , strlen($baseDir) + 1 );

        
        $this->logger->debug( sprintf('%s  %-5s  %s', 
            substr($md5sum,0,6),
            $role,
            $filepath
        ),1);
        $newfile = $dir->addChild('file');
        $newfile->addAttribute( 'install-as' , $target_filepath );
        $newfile->addAttribute( 'name'       , $filepath );
        $newfile->addAttribute( 'role'       , $role );
        $newfile->addAttribute( 'md5sum'     , $md5sum );
    }

    function generatePackageXml()
    {
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
            $logger = $this->logger;
            $logger->info('Building contents section...');
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

            $logger = $this->logger;
            $logger->info('Building dependencies section...');

            $deps = $xml->addChild('dependencies');
            $this->buildDependencySection('required',$deps,$config);

            if( $config->has('optional') )
                $this->buildDependencySection('optional',$deps,$config);


            # TODO: support phprelease tag.
            $xml->addChild('phprelease');


            // use DOMDocument to reformat package.xml
            if( class_exists('DOMDocument') ) {
                $logger->info2("* Re-formating XML...",1);
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



