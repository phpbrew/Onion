<?php

class ChannelTest extends PHPUnit_Framework_TestCase
{
    function testChannelPear()
    {
        $channel = new PEARX\Channel( 'pear.php.net' );
        ok( $channel );
    }

}

