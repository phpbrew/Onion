<?php

class ChannelParserTest extends PHPUnit_Framework_TestCase
{
    function testPearChannel()
    {
        $parser = new PEARX\ChannelParser;
        ok( $parser );

        $channel = $parser->parse( 'http://pear.php.net/channel.xml' );
        ok( $channel );
    }
}

