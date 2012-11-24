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
use Exception;

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

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function __construct()
    {
        $this->pool = new DependencyPool;
        $this->logger = \Onion\Application::getInstance()->getLogger();
    }

    public function resolvePearPackage($package, $depInfo)
    {
        if( $this->pool->hasPackage( $package ) ) {
            // xxx: check existing package version requirement..
            return;
        }

        // get installed version, compare version
        $this->pool->addPackage($package);

        // get dependent package info
        $this->logger->info( "Resolving PEAR package dependency: {$package->getId()}" );

        // use latest version by default, if version requirement is not defined.
        $targetVersion = $package->latest;
        if( isset($depInfo['version']) && ! empty($depInfo['version']) ) {
            $releaseVersions = array_keys($package->deps);
            $availableVersions = array();

            if( isset($depInfo['version']['min']) ) {
                $minVersion = $depInfo['version']['min'];

                $this->logger->info( 'Require ' .  $package->getId() . ' >= ' . $minVersion );

                $availableVersions = array_filter( $releaseVersions, function($releaseVersion) use($minVersion) {
                    return version_compare( $releaseVersion , $minVersion ) >= 0;
                });
            }

            if( isset($depInfo['version']['max']) ) {
                $maxVersion = $depInfo['version']['max'];

                $this->logger->info( 'Require ' .  $package->getId() . ' <= ' . $maxVersion );

                $availableVersions = array_filter( $availableVersions, function($releaseVersion) use($maxVersion) {
                    return version_compare( $releaseVersion , $maxVersion ) <= 0;
                });
            }
            if( empty($availableVersions) ) {
                throw new Exception("Non of available version for " . $package->name );
            }

            $this->logger->info('Found version: ' . join(', ', $availableVersions) );

            $targetVersion = end($availableVersions);
        } 

        if( isset( $package->deps[$targetVersion]['required']['extension']) ) {
            foreach( (array) $package->deps[ $targetVersion ]['required']['extension'] as $extension ) {
                // XXX: install extensions
            }
        }

        /*
         * // xxx
        $php = $package->deps[ $version ]['required']['php'];
        $php = $package->deps[ $version ]['required']['pearinstaller'];
        */
        if( isset( $package->deps[ $targetVersion ]['required']['package']) ) {
            $pkgs = $package->deps[ $targetVersion ]['required']['package'];

            // sometimes it's not list, so wrap it with list.
            if( ! isset($pkgs[0]) )
                $pkgs = array( $pkgs );

            foreach( $pkgs as $dep ) {
                $packageName = $dep['name'];
                $host = $dep['channel'];

                $this->logger->info2("Discovering channel $host for $packageName",1);
                $channel = new \PEARX\Channel( $host, array(
                    'cache' => \Onion\Application::getInstance()->getCache(),
                    'downloader' => \Onion\Downloader\CurlDownloaderFactory::create(
                        $this->logger->level == 2 // quiet, but should throw error and exceptions
                    ),
                ));
                $depPackage = $channel->findPackage( $packageName );
                $this->resolvePearPackage( $depPackage , $dep );
            }
        }
    }

    public function resolve( $package )
    {
        // expand package and package dependencies to package object
        // if installed , check if upgrade is need ?
        if( ! $package->local )
            $this->pool->addPackage($package);

        $app = \Onion\Application::getInstance();

        // expand package dependencies
        $deps = $package->getDependencies();
        foreach( $deps as $dep ) {

            // Expand pear package (refacotr this to dependencyInfo object)
            if( $dep['type'] == 'pear' ) {
                $depPackageName = $dep['name'];
                $this->logger->info2("Tracking dependency for PEAR package: {$dep['name']} ..." , 1);
                if( $dep['resource']['type'] == 'channel' ) {
                    $host = $dep['resource']['channel'];
                    $channel = new \PEARX\Channel( $host , array( 
                        'cache' => $app->getCache(),
                        'downloader' => \Onion\Downloader\CurlDownloaderFactory::create(
                            $app->getLogger()->level == 2 // --quiet option
                        ),
                    ));
                    $depPackage = $channel->findPackage( $depPackageName );

                    // discover pear channel
                    // $channel->prefetchPackagesInfo();
                    // $depPackage = $channel->getPackage( $depPackageName );
                    $this->resolvePearPackage( $depPackage , $dep );
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
