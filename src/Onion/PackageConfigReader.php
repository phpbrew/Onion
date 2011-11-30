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
            $cx->logger->error('package name is not defined.');
            # echo "\n\n";
            # echo "\t[package]\n";
            # echo "\tname = {your package name}\n\n";
            exit(1);
        }

        if( ! $config->has('package.version') ) {
            $cx->logger->error('package version is not defined.');
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
            $config['package']['summary'] = $config['package']['desc'];  # use desc as summary as default.
        }

        if( ! $config->has('package.license') ) {
            $cx->logger->info("license is not defined., use PHP license by default.");
            $config->set('package.license','PHP LICENSE');
        }


        // XXX: check authors[] config


        /* XXX: support license section
            *
            * <license uri="http://www.opensource.org/licenses/bsd-license.php">BSD Style</license>
            */

        if( ! $config->has('package.channel' ) ) {
            $cx->logger->info("package channel is not defined. use pear.php.net by default.");
            $config->set('package.channel','pear.php.net');
        }

        if( ! $config->has('package.date') ) {
            $date = date('Y-m-d');
            $cx->logger->info("package date is not defined. use current date $date by default.");
            $config->set('package.date',$date);
        }

        if( ! $config->has('package.time') ) {
            $time = strftime('%X');
            $cx->logger->info("package time is not defined. use current time $time by default.");
            $config->set('package.time',strftime('%X'));
        }

        if( ! $config->has('package.notes') ) {
            $config->set('package.notes','-');
        }

        // apply api_version from 'version', if not specified.
        if( ! $config->has('package.version-api') ) {
            $config->set('package.version-api',$config->get('package.version'));
        }

        if( $config->has('stability') ) {
            $s = $config->get('stability');
            $config->set('stability-release', $s );
            $config->set('stability-api', $s );
        }

        if( ! $config->has('stability') &&
            ! $config->has('stability-release') &&
            ! $config->has('stability-api') ) {
            $cx->logger->info("stability is not set, use alpha by default");
            $config->set('stability-release', 'alpha' );
            $config->set('stability-api', 'alpha' );
        }

        /* XXX: check stability valid keywords */


        /* checking dependencies */
        $cx->logger->info("Checking dependencies...");
        if( ! $config->has('requires') )  {
            $config->requires = array( 
                'php' => '5.3',
                'pearinstaller' => '1.4',
            );
        }

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
            $stability->release = $config->get('stability-release');  # XXX: detect from version number.
            $stability->api     = $config->get('stability-api');

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

            // build contents section, TODO: support [structure] section.
            $contents = $xml->addChild('contents');
            $dir = $contents->addChild('dir');
            $dir->addAttribute('name','/');
            $srcDir = 'src';
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($srcDir),
                                    RecursiveIteratorIterator::CHILD_FIRST);
            foreach( $iterator as $path ) {
                if( $path->isFile() ) {
                    # substr( $path->__tostring()  );
                    $filepath = $path->getPathname();
                    $md5sum   = md5_file($filepath);
                    $target_filepath = substr( $filepath , strlen($srcDir) + 1 );
                    echo substr($md5sum,0,6) . '   ' . $target_filepath . ' ' . "\n";

                    $role = 'data';
                    if( preg_match('/\.php$/',$filepath) ) {
                        $role = 'php';
                    }

                    # <file install-as="Twig/Autoloader.php" md5sum="b60338d1df4f145c7318d8f870925d1e" name="lib/Twig/Autoloader.php" role="php" />
                    $newfile = $dir->addChild('file');
                    $newfile->addAttribute( 'install-as' , $target_filepath );
                    $newfile->addAttribute( 'name'       , $filepath );
                    $newfile->addAttribute( 'role'       , $role );
                    $newfile->addAttribute( 'md5sum'     , $md5sum );
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
            $deps = $xml->addChild('dependencies');
            $required_el = $deps->addChild('required');

            foreach( $config->get('requires') as $package_name => $arg ) 
            {
                if( $package_name == 'extensions' ) {
                    foreach( $arg as $extension ) {
                        $pkg_el = $required_el->addChild('extension');
                        $pkg_el->name = $extension;
                    }
                }
                // php or pear-intsaller
                elseif( in_array($package_name, array('pearinstaller','php') ) ) {
                    $required = SpecUtils::parseVersion( $arg );
                    $pkg_el = $required_el->addChild( $package_name );
                    $pkg_el->addChild( 'min' , $required['min'] );
                }
                // for normal package
                else {
                    $channel = null;
                    if( strpos( $package_name , '/' ) !== false )
                        list($channel,$package_name) = explode('/',$package_name);

                    $required = SpecUtils::parseVersion( $arg );
                    if( ! $required ) {
                        if( preg_match('/http:\/\//',$arg) ) {
                            $pkg = $required_el->addChild('package');
                            $pkg->name = $package_name;
                            $pkg->uri  = $arg;
                        }
                    } else {
                        $pkg = $required_el->addChild('package');
                        $pkg->name    = $package_name;
                        $pkg->channel = $channel;
                        $pkg->min     = $required['min'];
                    }
                }
            }


            $xml->addChild('phprelease');

            # TODO: support phprelease tag.
            # <phprelease />
            return $xml->asXML();
        } 
        catch (Exception $e) 
        {
            die( $e->getMessage() );
        } 
    }

}

