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
 *      $pkginfo = $reader->read( 'file.ini' );
 *
 *      $pkginfo->get( 'config' );
 *      $pkginfo->get( 'config.name' );
 *      $pkginfo->get( 'section.name' );
 *
 *      $pkgxml = new PackageXmlGenerator( $pkginfo );
 *      $pkgxml->useDefaultOptions(true);
 *      $pkgxml->setReformat(true);
 *      $pkgxml->generate('package.xml');
 *
 */
class PackageConfigReader 
{
    public $validate = false;

    function __construct( $options = array() )
    {
        if( isset($options['validate']) )
            $this->validate = $options['validate'];

    }

    function read($file)
    {
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

        // build package meta info
        $pkginfo->name      = $config->get('package.name');
        $pkginfo->desc      = $config->get('package.desc');
        $pkginfo->summary   = $config->get('package.summary');
        $pkginfo->version   = $config->get('package.version');
        $pkginfo->stability = $config->get('package.stability');

        $pkginfo->license          = $config->get('package.license');
        $pkginfo->licenseUri       = $config->get('package.license.uri');


        // read dependency sections
        // checking dependencies
        $requires = $config->get('require');
        if( $requires ) {
            foreach( $requires as $key => $value ) 
            {
                $type = $this->detectDependencyType( $key , $value );
                switch($type) {

                case 'core':
                    $pkginfo->addDependency('core',$key, $value);
                    break;

                case 'extension':
                    $depinfo = $this->parseDependency($key,$value);
                    $pkginfo->addDependency('extension',$depinfo['name'],$depinfo['require']);
                    break;

                case 'pear':
                    $depinfo = $this->parseDependency($key,$value);
                    $pkginfo->addDependency('pear',$depinfo['name'],$depinfo['require'],$depinfo['resource']);
                    break;

                default:
                    throw new InvalidConfigException("Unsupported dependency type: $type ");
                    break;

                }
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
		if( preg_match('/^([a-zA-Z0-9.-]+)\/(\w+)$/' , $key , $regs ) ) 
		{
			if( $value != 'conflict' )
			{
				return array(
					'type'     => 'pear',
					'name'     => $regs[2],
					'require' => SpecUtils::parseVersion($value),
					'resource' => array( 
						'type'     => 'pear',
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
						'type'     => 'pear',
						'channel' => $regs[1],
					)
				);
			}

		}
		elseif( preg_match('/^ext(?:ension)?\/(\w+)$/',$key,$regs) ) {
			return array(
				'type'    => 'extension',
				'name'    => $regs[1],
				'require' => SpecUtils::parseVersion($value),
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
}






