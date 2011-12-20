<?php
/*
 * This file is part of the {{ }} package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace tests\Onion\Pear;

class ChannelParserTest extends \PHPUnit_Framework_TestCase 
{
    function test() 
    {
        $parser = new \Onion\Pear\ChannelParser;
        ok( $parser );

        $channel = $parser->parse( 'tests/data/pear2.channel.xml' );
        ok( $channel );
    }
}

