<?php
namespace Onion\Pear;
use PHPUnit_Framework_TestCase;
use Onion\Pear\ChannelDiscover;

class ChannelDiscoverTest extends \PHPUnit_Framework_TestCase
{
    function test()
    {
        $discover = new ChannelDiscover;
        ok( $discover );

        $channel = $discover->lookup( 'pear.dev' );
        ok( $channel );
        isa_ok( '\Onion\Pear\Channel' , $channel );

        $packages = $channel->getAllPackages();

    }
}


