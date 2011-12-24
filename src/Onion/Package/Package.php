<?php
/*
 * This file is part of the Onion package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Onion\Package;
use Onion\Package\BasePackage;
use Onion\Package\PackageInterface;

/**
 * package class for package.ini
 */
class Package extends BasePackage
    implements PackageInterface
{
    public $name;
    public $version;
    public $desc;
    public $summary;

    public $apiVersion;

    public $license;
    public $licenseUri;

    /** 
     * main stability 
     */
    public $stability;

    /**
     * api stability
     */
    public $apiStability;

    /**
     * release stability
     */
    public $releaseStability;


    /**
     * package dependencies
     *
     *  $pkginfo->deps[] = array(
     *      'type' => 'extension',
     *      'name' => $depinfo['name'],
     *      'version' => $depinfo['version'],
     *  );
     *
     *  types could be core, extension, pear ... etc
     */
    public $deps = array();

    /** 
     * ConfigContainer object
     */
    public $config;


    public function getDefaultStructureConfig()
    {
        // directory structure
        return array(
            'doc'    => array('doc','examples'),
            'tests'  => (array) 'tests',
            'php'    => (array) 'src',
            'script' => (array) 'bin',
            'data'   => (array) 'data',
        );
    }


    public function getDependencies()
    {
        return $this->deps;
    }

    public function getId()
    {

    }

}

