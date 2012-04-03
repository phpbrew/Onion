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


    /**
     * @dataProvider getChannels
     */
    public function testChannel($host)
    {
        $channel = new PEARX\Channel($host);
        ok( $channel );

        $url = $channel->getRestBaseUrl();
        ok( $url );

        $categories = $channel->getCategories();
        ok( $categories );

        foreach( $categories as $category ) {
            ok( $category->name , 'category name' );

            $packages = $category->getPackages();
            foreach( $packages as $package ) {
                ok( $package->name );
                ok( $package->summary );
                ok( $package->desc );
                ok( $package->license );

                foreach( $package->releases as $r ) {
                    ok( $r->version );
                    ok( $r->stability );
                }
            }
        }
    }

}

