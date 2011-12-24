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

namespace tests\Onion\Dependency;
class DependencyResolverTest extends \PHPUnit_Framework_TestCase 
{
    function test() 
    {
        $logger = new \CLIFramework\Logger;
        ok( $logger );

        $reader = new \Onion\PackageConfigReader;
        $reader->setLogger( $logger );

        ob_start();
        $pkg = $reader->read( 'tests/data/package.ini' );
        ob_end_clean();
        $pkg->local = 1; // dont install 

        ok( $pkg );
        ok( $pkg->name );
        ok( $pkg->version );
        ok( $pkg->summary );
        ok( $pkg->desc );
        ok( $pkg->stability );

        ok( $pkg->getId() );
        ok( $pkg->getDependencies() );

        $dr = new \Onion\Dependency\DependencyResolver;
        $dr->resolve( $pkg );

        $manager = $dr->getManager();
        ok( $manager );

        $packages = $manager->getPackages();

        // var_dump( $packages ); 
        foreach( $packages as $package ) {
            ok( $package );
            ok( $package->getId() );
            echo get_class( $package ) . "\n";

            // echo $package->getId() . "\n";
        }

        $installer = new \Onion\Installer( $manager );
        $installer->install();
    }
}

