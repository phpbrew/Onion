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
 * General Onion Package
 *
 * Package class for package.ini
 */
class Package implements PackageInterface
{
    public $name;
    public $version;
    public $desc;
    public $summary;


    public $license;

    // optional
    public $licenseUri;

    /** 
     * main stability (optional)
     */
    public $stability;


    public $dependencies = array();

    /** 
     * ConfigContainer object
     */
    public $config;


    // local flag (not to install)
    public $local;

    public function addDependency($type,$name,$require,$resource = array() )
    {
        $require = \Onion\SpecUtils::parseVersion($require);
        $this->dependencies[] = array( 
            'type' => $type,
            'name' => $name,
            'require' => $require,
            'resource' => $resource,
        );
    }



    public function getDependencies()
    {
        return $this->dependencies;
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


