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
use Onion\Dependency\DependencyManager;

/**
 *
 * $dr = new DependencyResolver;
 * $dependencyQueue = $dr->resolve( Onion\Package\Package $package );
 *
 */
class DependencyResolver 
{
    public $pool;

    function __construct()
    {
        $this->pool = new DependencyManager;
    }

    function resolvePearPackage($package)
    {
        $pId = $package->getId();

        // get dependent package info
        echo "Resolving PEAR package: {$package->name} \n";
        $version = $package->latest;

        if( isset( $package->deps[ $version ]['required']['extension']) )
            foreach( $package->deps[ $version ]['required']['extension'] as $extension ) {
                // xxx:

            }

        /*
         * // xxx
        $php = $package->deps[ $version ]['required']['php'];
        $php = $package->deps[ $version ]['required']['pearinstaller'];
        */
        if( isset( $package->deps[ $version ]['required']['package']) ) {
            foreach( $package->deps[ $version ]['required']['package'] as $dep ) {
                $packageName = $dep['name'];
                $channelHost = $dep['channel'];

                // discover pear channel
                $discover = new \Onion\Pear\ChannelDiscover;
                $channel = $discover->lookup( $channelHost );
                $channel->prefetchPackagesInfo();
                $depPackage = $channel->getPackage( $packageName );
                $this->resolvePearPackage( $depPackage );
            }
        }
    }

    function resolve( $package )
    {
        // expand package and package dependencies to package object
        if( is_a( $package ,'\Onion\Package\Package' ) ) 
        {
            $pId = $package->getId();

            // if installed , check if upgrade is need ?

            // expand package dependencies
            $deps = $package->getDependencies();
            foreach( $deps as $dep ) {



                // Expand pear package (refacotr this to dependencyInfo object)
                if( $dep['type'] == 'pear' ) {
                    $depPackageName = $dep['name'];
                    echo "Tracking dependency for PEAR package: {$dep['name']} ...\n";
                    if( $dep['resource']['type'] == 'channel' ) {
                        $channelHost = $dep['resource']['channel'];

                        // discover pear channel
                        $discover = new \Onion\Pear\ChannelDiscover;
                        $channel = $discover->lookup( $channelHost );
                        $channel->prefetchPackagesInfo();
                        $depPackage = $channel->getPackage( $depPackageName );
                        $this->resolvePearPackage( $depPackage );
                    }
                }
                elseif( $dep['type'] == 'extension' ) {
                    $depExtensionName = $dep['name'];
                    echo "Tracking dependency for extension: {$dep['name']} ...\n";


                }
            }
        }
        elseif( is_a( $package , '\Onion\Package\PearPackage' ) ) {

        }
        elseif( is_a( $package , '\Onion\Package\LibraryPackage' ) ) {

        }
    }

}
