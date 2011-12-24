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

class DependencyManager
{

    /**
     * core dependencies:
     *    like php, pearinstaller, extensions
     */
    public $coreDeps = array();
    public $coreDepsByName = array();


    /**
     * contains packages
     */
    public $packages = array();
    public $packagesByName = array();


    function addPackage($package)
    {
        // check package
        if( isset( $this->packagesByName[ $package->name ] ) ) {
            // already defined, check the requirement or conflicts.


        } else {
            $this->packages[] = $this->packagesByName[ $package->name ] = $package;
        }
    }

    function addCoreDependency($name,$requirements)
    {
        if( isset( $this->coreDepsByName[ $package->name ] )  {
            // already defined, check the requirement or conflicts.

        }
        else {
            $this->coreDeps[] = $this->coreDepsByName[ $name ] = $requirements;
        }
    }

}
