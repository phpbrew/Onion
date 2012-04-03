<?php

class ChannelTest extends PHPUnit_Framework_TestCase
{

    public function getChannels()
    {
        return array(
            // array( 'pear.php.net' ),
            // array( 'pear.zfcampus.org' ),
            array( 'pear.corneltek.com' ),
        );
    }


    public function testPackageFind()
    {
        $cache = new CacheKit\FileSystemCache(array(
            'expiry' => 10,
            'cache_dir' => 'tests/tmp',
        ));
        $channel = new PEARX\Channel('pear.corneltek.com', array( 
            'cache' => $cache,
        ));

        $package = $channel->findPackage('Onion');
        ok( $package );
        ok( $package->name );
        is( 'Onion' , $package->name );
        ok( $package->summary );
        ok( $package->license );
    }

    /**
     * @dataProvider getChannels
     */
    public function testChannel($host)
    {
        $channel = new PEARX\Channel($host);
        ok( $channel );

        $url = $channel->getRestBaseUrl();
        ok( $url );

        $packages = $channel->getPackages();

        foreach( $packages as $p ) {
            ok( $p );
        }

        $categories = $channel->getCategories();
        ok( $categories );

        foreach( $categories as $category ) {
            ok( $category->name , 'category name' );

            $packages = $category->getPackages();
            foreach( $packages as $packageName => $package ) {
                ok( $package->name );
                ok( $package->summary );
                ok( $package->desc );
                ok( $package->license );
                ok( $package->deps );

                foreach( $package->releases as $r ) {
                    ok( $r->version );
                    ok( $r->stability );
                }
            }
        }
    }

}

