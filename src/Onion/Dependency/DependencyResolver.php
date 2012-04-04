<?php
/**
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
        $this->pool = new DependencyPool;
        $this->logger = \Onion\Application::getLogger();
    }

    function resolvePearPackage($package)
    {
        if( $this->pool->hasPackage( $package ) ) {
            // xxx: check existing package version requirement..
            return;
        }

        // get installed version, compare version
        $this->pool->addPackage($package);

        // get dependent package info
        $this->logger->info( "Resolving PEAR package dependency: {$package->getId()}" );
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
                $host = $dep['channel'];

                $this->logger->info2("Discovering channel $host for $packageName",1);

                $channel = new \PEARX\Channel( $host, array(
                    'cache' => \Onion\Application::getInstance()->getCache(),
                    'downloader' => \Onion\Downloader\CurlDownloaderFactory::create(),
                ));
                $depPackage = $channel->findPackage( $packageName );
                $this->resolvePearPackage( $depPackage );
            }
        }
    }

    function resolve( $package )
    {
        // expand package and package dependencies to package object
        // if installed , check if upgrade is need ?
        if( ! $package->local )
            $this->pool->addPackage($package);

        // expand package dependencies
        $deps = $package->getDependencies();
        foreach( $deps as $dep ) {

            // Expand pear package (refacotr this to dependencyInfo object)
            if( 'pear' === $dep['type'] ) {
                $depPackageName = $dep['name'];
                $this->logger->info2("Tracking dependency for PEAR package: {$dep['name']} ..." , 1);
                if( $dep['resource']['type'] == 'channel' ) {
                    $host = $dep['resource']['channel'];

                    $channel = new \PEARX\Channel( $host , array( 
                        'cache' => \Onion\Application::getInstance()->getCache(),
                        'downloader' => \Onion\Downloader\CurlDownloaderFactory::create(),
                    ));
                    $depPackage = $channel->findPackage( $depPackageName );

                    // discover pear channel
                    // $channel->prefetchPackagesInfo();
                    // $depPackage = $channel->getPackage( $depPackageName );
                    $this->resolvePearPackage( $depPackage );
                }
            }
            elseif( $dep['type'] == 'extension' ) {
                $depExtensionName = $dep['name'];
                $this->logger->info2("Tracking dependency for extension: {$dep['name']} ..." , 1);
            }
        }
    }

    public function getPool()
    {
        return $this->pool;
    }

}
