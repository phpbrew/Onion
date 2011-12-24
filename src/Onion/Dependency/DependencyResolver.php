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
    public $logger;

    function __construct()
    {
        $this->manager = new DependencyManager;
        $this->logger = \Onion\Application::getLogger();
    }

    function resolvePearPackage(\Onion\Pear\Package $package)
    {
        if( $this->manager->hasPackage( $package ) ) {
            // xxx: check existing package version requirement..
            return;
        }

        // get installed version, compare version
        $this->manager->addPackage($package);

        // get dependent package info
        $this->logger->info( "Resolving PEAR package dependency: {$package->name}" );
        $version = $package->latest;

        if( isset( $package->deps[ $version ]['required']['extension']) ) {
            foreach( (array) $package->deps[ $version ]['required']['extension'] as $extension ) {
                // xxx:

            }
        }

        /*
         * // xxx
        $php = $package->deps[ $version ]['required']['php'];
        $php = $package->deps[ $version ]['required']['pearinstaller'];
        */
        if( isset( $package->deps[ $version ]['required']['package']) ) {
            $pkgs = $package->deps[ $version ]['required']['package'];

            // sometimes it's not list, so wrap it with list.
            if( ! isset($pkgs[0]) )
                $pkgs = array( $pkgs );

            foreach( $pkgs as $dep ) {
                $packageName = $dep['name'];
                $channelHost = $dep['channel'];


                $this->logger->info2("Discovering channel $channelHost for $packageName",1);

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

            // if installed , check if upgrade is need ?
            if( ! $package->local )
                $this->manager->addPackage($package);


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
            // xxx: support pear package

        }
        elseif( is_a( $package , '\Onion\Package\LibraryPackage' ) ) {
            // xxx: support library package


        }
    }

    function getManager()
    {
        return $this->manager;
    }

}
