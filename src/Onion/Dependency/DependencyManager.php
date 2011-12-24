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
 * dependency manager
 *
 * it's a dependency pool, contains packages and core dependencies like extension or php requirements.
 *
 * we use dependency resovlver to resolve the dependency tree of dependency manager.
 */
class DependencyManager
{
    /**
     * core dependencies:
     *    like php, pearinstaller, extensions
     *
     *    xxx; currently not used.
     */
    public $coreDeps = array();
    public $coreDepsById = array();


    /**
     * contains packages
     */
    public $packages = array();
    public $packagesByName = array();



    function hasPackage( \Onion\Package\PackageInterface $package)
    {
        return isset( $this->packagesByName[ $package->getId() ] );
    }


    function addPackage( \Onion\Package\PackageInterface $package)
    {
        // check package
        if( isset( $this->packagesByName[ $package->getId() ] ) ) {
            // already defined, check the requirement or conflicts.


        } else {
            $this->packages[] = $this->packagesById[ $package->getId() ] = $package;

            
            // todo: traverse package's dependency and expand them...
        
        }
    }


    /**
     * requirements:
     *
     *    array(
     *      'version' => array( 'min' => ... , 'max' => ... ),
     *    );
     */
    function addCoreDependency( $name, $requirements )
    {
        if( isset( $this->coreDepsById[ $package->name ] )  ) {
            // already defined, check the requirement or conflicts.

        }
        else {
            $this->coreDeps[] = $this->coreDepsById[ $name ] = $requirements;
        }
    }

    function removePackage($name)
    {
        unset( $this->packagesById[ $name ] );
    }


}
