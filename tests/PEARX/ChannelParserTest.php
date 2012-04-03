<?php

class ChannelParserTest extends PHPUnit_Framework_TestCase
{
    function testPearChannel()
    {
        $parser = new PEARX\ChannelParser;
        ok( $parser );

        $info = $parser->parse( 'http://pear.php.net/channel.xml' );
        ok( $info );

        $info = $parser->parse( 'http://pear.zfcampus.org/channel.xml' );
        ok( $info );

        is( 'REST1.1', $info->rest, 'REST version' );
        ok( $info->name );
        ok( $info->primary[ $info->rest ] );
    }
}

