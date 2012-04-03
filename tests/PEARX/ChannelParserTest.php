<?php

class ChannelParserTest extends PHPUnit_Framework_TestCase
{

    function getChannels()
    {
        return array( 
            // array( 'http://pear.php.net/channel.xml' ),
            array( 'http://pear.zfcampus.org/channel.xml' ),
        );
    }

    /**
     * @dataProvider getChannels
     */
    function testPearChannel($url)
    {
        $parser = new PEARX\ChannelParser;
        ok( $parser );

        $info = $parser->parse( $url );
        ok( $info->rest, 'REST version' );
        ok( $info->name );
        ok( $info->primary[ $info->rest ] );
    }
}

