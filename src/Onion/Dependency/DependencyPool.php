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
namespace Onion\Dependency;


/**
 * dependency pool
 *
 * it's a dependency pool, contains packages and core dependencies like extension or php requirements.
 *
 * we use dependency resovlver to resolve the dependency tree of dependency manager.
 */
class DependencyPool
{
    /**
     * contains packages
     */
    public $packages = array();
    public $packagesById = array();



    function hasPackage($package)
    {
        return isset( $this->packagesById[ $package->getId() ] );
    }


    function addPackage($package)
    {
        // check package
        if( isset( $this->packagesById[ $package->getId() ] ) ) {
            // xxx: if already defined, check the requirement or conflicts.

        } else {
            $this->packages[] = $this->packagesById[ $package->getId() ] = $package;
        }
    }

    function removePackage($name)
    {
        unset( $this->packagesById[ $name ] );
    }

    function getPackage($id)
    {
        return $this->packagesById[ $id ];
    }


    /**
     * return all packages 
     */
    function getPackages()
    {
        return $this->packages;
    }

}
