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
use Onion\ConfigFile;
use SimpleXMLElement;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use DOMDocument;

class PackageConfigFile extends ConfigFile
{

    function read()
    {
        parent::read();

        // post-processing
        $config = & $this->config;
        // default values for package config
        if( ! isset($config['package']['summary']) )
            $config['package']['summary'] = $config['package']['desc'];  # use desc as summary as default.

        if( isset($config['package']['author']) )
            $config['package']['authors'][] = $config['package']['author'];

        /* XXX: support license section
            *
            * <license uri="http://www.opensource.org/licenses/bsd-license.php">BSD Style</license>
            */
        if( ! isset($config['package']['license']) )
            $config['package']['license'] = 'PHP LICENSE';

        if( ! isset($config['package']['channel'] ) )
            $config['package']['channel'] = 'pear.php.net';

        if( ! isset($config['package']['date'] ) )
            $config['package']['date'] = date('Y-m-d');

        if( ! isset($config['package']['time'] ) )
            $config['package']['time'] = strftime('%X');

        if( ! isset($config['package']['notes'] ) )
            $config['package']['notes'] = '-';

        // apply api_version from 'version', if not specified.
        if( ! isset($config['package']['api_version'] ) )
            $config['package']['api_version'] = $config['package']['version'];

        if( ! isset($config['requires']) ) 
            $config['requires'] = array( 
                'php' => '5.3',
                'pearinstaller' => '1.4',
            );
    }

    function validate()
    {

    }

    function parseVersionString($string)
    {
        if( preg_match('/^([0-9.]+)$/',$string,$regs) ) {
            return array( 'min' => $regs[1] );
        }
        if( preg_match('/^\s*([>=]+)\s*([0-9.]+)$/',$string,$regs) ) {
            return array( 'min' => $regs[2] );
        }
    }

    function parseAuthorString($string)
    {
        $author = array();
        // parse author info:   {Name} ({Id}) <{email}>
        if( preg_match( '/^\s*
                    (.+?)
                    \s*
                    \((\S+)\)
                    \s*
                    <(\S+)>
                    \s*$/x' , $string , $regs ) ) 
        {
            list($orig,$name,$user,$email) = $regs;
            $author['name'] = $name;
            $author['user'] = $user;
            $author['email'] = $email;
        }

        // parse author info:  {Name} <{email}>
        elseif( preg_match( '/^\s*
                    (.+?)
                    \s*
                    <(\S+)>
                    \s*$/x' , $string , $regs ) )
        {
            list($orig,$name,$email) = $regs;
            $author['name'] = $name;
            $author['email'] = $email;
        }
        else {
            $author['name'] = $string;
        }
        return $author;
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
            $xml->name        = $config['package']['name'];
            $xml->channel     = $config['package']['channel'];
            $xml->summary     = $config['package']['summary'];
            $xml->description = $config['package']['desc'];


            $author_active = true;
            foreach( $config['package']['authors'] as $author ) {
                $lead = $xml->addChild('lead');
                $data =  $this->parseAuthorString( $author );
                foreach( $data as $k => $v )
                    $lead->$k = $v;
                if( $author_active ) {
                    $lead->active = 1;
                    $author_active = false;
                }
            }

            $xml->date        = $config['package']['date'];
            $xml->time        = $config['package']['time'];

            $version          = $xml->addChild('version');
            $version->release = $config['package']['version'];
            $version->api     = $config['package']['api_version'];

            $stability        = $xml->addChild('stability');
            $stability->release = 'alpha';  # XXX: detect from version number.
            $stability->api     = 'alpha';

            $xml->license     = $config['package']['license'];
            $xml->notes       = $config['package']['notes'];



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

            if( isset($config['requires']) )
            foreach( $config['requires'] as $package_name => $arg ) 
            {
                if( $package_name == 'extensions' ) {
                    foreach( $arg as $extension ) {
                        $pkg_el = $required_el->addChild('extension');
                        $pkg_el->name = $extension;
                    }
                }
                // php or pear-intsaller
                elseif( in_array($package_name, array('pearinstaller','php') ) ) {
                    $required = $this->parseVersionString( $arg );
                    $pkg_el = $required_el->addChild( $package_name );
                    $pkg_el->addChild( 'min' , $required['min'] );
                }
                // for normal package
                else {
                    $channel = null;
                    if( strpos( $package_name , '/' ) !== false )
                        list($channel,$package_name) = explode('/',$package_name);

                    $required = $this->parseVersionString( $arg );
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


            // use DOMDocument to reformat package.xml
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($xml->asXML());
            return $dom->saveXML();

        } 
        catch (Exception $e) 
        {
            die( $e->getMessage() );
        } 
    }

}

