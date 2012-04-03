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

        $url = $channel->info->getRestBaseUrl();
        ok( $url );

        $categories = $channel->fetchCategories();
    }

}

