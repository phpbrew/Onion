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

        $resource = $discover->lookup( 'pear.dev' );
        ok( $resource );

    }
}


