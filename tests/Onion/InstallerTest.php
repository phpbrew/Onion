<?php

class InstallerTest extends PHPUnit_Framework_TestCase
{
    function test()
    {

        $reader = new Onion\PackageConfigReader;
        ok($reader);

        $pkg = $reader->read( 'tests/data/package.ini' );
        ok( $pkg );
        $pkg->local = 1; // dont install local package

        $dr = new Onion\Dependency\DependencyResolver;
        $dr->resolve( $pkg );

        ok($dr);


        // create installer
        $installer = new Onion\Installer( array( 
            'lib_dir' => 'tests/tmp/vendor'
        ));
        ok($installer);

#          $packages = $pool->getPackages();
#          foreach( $packages as $package ) {
#              $installer->install( $package );
#          }
    }
}


