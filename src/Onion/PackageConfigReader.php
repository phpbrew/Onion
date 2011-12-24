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

        if( $s = $config->get('package.stability') ) {
            $config->set('package.stability.api' , $s );
            $config->set('package.stability.release' , $s );
        }

        if( ! $config->has('package.stability.api') )
            $config->set('package.stability.api','alpha');

        if( ! $config->has('package.stability.release') )
            $config->set('package.stability.release','alpha');

        if( ! $config->has('package.version.api') )
            $config->set('package.version.api' , $config->get('package.version') );



        // preprocess, validate sections only for package.ini
        $pkginfo = new Package;
        $pkginfo->config = $config;

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

        if( $config->has('required') )
            $logger->warn( 'section "required" has been renamed to "require".' );

        if( ! $config->has('require') ) {

            // use default core dependency 
            $logger->info2("* required section is not defined. use php 5.3 and pearinstaller 1.4 by default.",1);
            $pkginfo->coreDeps[] = array(
                'type' => 'core',
                'name' => 'php',
                'version' => array( 'min' => '5.3' ),
            );
            $pkginfo->coreDeps[] = array( 
                'type' => 'core',
                'name' => 'pearinstaller',
                'version' => array( 'min' => '1.4' ),
            );
        }


        foreach( $config->get('require') as $key => $value ) 
        {
            $type = $this->detectDependencyType( $key , $value );
            
            switch($type) {

            case 'core':
                $version = SpecUtils::parseVersion( $value );
                $pkginfo->coreDeps[] = array( 
                    'type' => 'core',
                    'name' => $key,
                    'version' => $version,  /* [ min => , max => ] */
                );
                break;

            case 'extension':
                $depinfo = $this->parseDependency($key,$value);
                $pkginfo->deps[] = array(
                    'type' => 'extension',
                    'name' => $depinfo['name'],
                    'version' => $depinfo['version'],
                );
                break;

            case 'pear':
                $depinfo = $this->parseDependency($key,$value);
                $pkginfo->deps[] = $depinfo;
                break;

            default:
                throw new InvalidConfigException("Unsupported dependency type: $type ");
                break;

            }
        }
        return $pkginfo;
    }

	/**
	 *
	 * format 1:
	 *		{channel}/{package name} = {version expression }
	 *
	 * format 2:
	 *
	 *		{package name} = {URI}
	 *
	 * format 3:
	 *		{package name} = {resource id}
     *
	 *
	 * */
	function detectDependencyType($key,$value = null)
	{
		if( in_array($key, array('pearinstaller','php') ) ) {
			return 'core';
		}
		// support extension/{extension name} = {version expression}
        elseif( preg_match('/^ext(?:ension)?\/\w+/',$key) ) {
			return 'extension';

        } 
        // todo: check if there is a resource for this.
        else {
            // otherwisze it's a package
            return 'pear';
        }
	}


	/**
     *
	 */
	function parseDependency($key,$value)
	{
		// format:  {channel host}/{package name} = {version expression}
		if( preg_match('/^([a-zA-Z0-9.]+)\/(\w+)$/' , $key , $regs ) ) 
		{
			if( $value != 'conflict' )
			{
				return array(
					'type'     => 'pear',
					'name'     => $regs[2],
					'version' => SpecUtils::parseVersion($value),
					'resource' => array( 
						'type'     => 'channel',
						'channel' => $regs[1],
					)
				);
			}
			else {
				return array(
					'type'     => 'pear',
					'name'     => $regs[2],
					'conflict' => 1,
					'resource' => array( 
						'type'     => 'channel',
						'channel' => $regs[1],
					)
				);
			}

		}
		elseif( preg_match('/^ext(?:ension)?\/(\w+)$/',$key,$regs) ) {
			return array(
				'type'    => 'extension',
				'name'    => $regs[1],
				'version' => SpecUtils::parseVersion($value),
			);
		}
		elseif( preg_match('/^(\w+)$/',$key,$regs) ) 
		{
			// PEAR package with URI format
			if( preg_match('/^https?:\/\//',$value) ) {
				return array(
					'type' => 'pear',
					'name' => $key,
					'resource' => array(
						'type' => 'uri',
						'uri'  => $value,
					),
				);
			}
		}
		else {
			throw new Exception("Unknown dependency type.");
		}
	}

    /**
     * return external package resource
     */
    public function getPackageResource($packageName)
    {
        if( $config->has( 'resource ' . $packageName ) )
            return $config->get( 'resource ' . $packageName );
    }
}






