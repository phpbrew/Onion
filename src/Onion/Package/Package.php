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
class Package implements PackageInterface
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
     * @var \Onion\ConfigContainer
     */
    public $config;


    // local flag (not to install)
    public $local;


    public function getDefaultStructureConfig()
    {
        $configuredStructure = $this->config->get('structure');

        if (empty($configuredStructure)) {
            return array(
                'doc'    => array('doc', 'docs','examples'),
                // 'test'  => (array) 'tests',
                'php'    => (array) 'src',
                // xxx: better config for roles
                // 'script' => (array) 'bin',
                'data'   => (array) 'data',
            );
        }

        return $configuredStructure;
    }


    public function getDependencies()
    {
        return $this->deps;
    }

    /**
     * return package Id
     *
     * @return string
     */
    public function getId()
    {
        // xxx: should be with namespace
        return $this->name;
    }
}
