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

    public function getPool()
    {
        return $this->pool;
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
                    'downloader' => \Onion\Downloader\CurlDownloaderFactory::create(
                        $this->logger->level == 2 // quiet, but should throw error and exceptions
                    ),
                ));
                $depPackage = $channel->findPackage( $packageName );
                $this->resolvePearPackage( $depPackage );
            }
        }
    }


    /**
     * Resolve Onion Package
     */
    public function resolve( $package )
    {
        // expand package and package dependencies to package object
        // if installed , check if upgrade is need ?
        if( ! $package->virtual ) {
            $this->pool->addPackage($package);
        }

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
                        'downloader' => \Onion\Downloader\CurlDownloaderFactory::create(
                            $this->logger->level == 0 // --quiet option
                        ),
                    ));
                    $depPackage = $channel->findPackage( $depPackageName );

                    // find compatible release version
                    $targetVersion;
                    if( $require ) {
                        foreach( array_reverse($depPackage->releases) as $r ) {
                            if( isset($require['max']) ) {
                                if( version_compare($r->version,$require['max']) > 0 ) {
                                    continue;
                                }
                            }
                            if( isset($require['min']) ) {
                                if( version_compare($r->version,$require['min']) > 0 ) {
                                    $targetVersion = $r->version;
                                } 
                            }
                            // $r->version, $r->stability;
                        }
                        if( null === $targetVersion )
                            throw new Exception("No valid dependency found: $depPackageName");
                    }
                    else {
                        $targetVersion = $depPackage->latest;
                    }


                    // new \Onion\Operation\InstallOperation;
                    $this->resolvePearPackage( $depPackage );
                }
            }
            elseif( $dep['type'] == 'extension' ) {
                $depExtensionName = $dep['name'];
                $this->logger->info2("Tracking dependency for extension: {$dep['name']} ..." , 1);
                // XXX:
            }
        }
    }


}
