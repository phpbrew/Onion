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
        # $channel = new \Pyrus\ChannelFile( 'tests/data/pear2.channel.xml' );
        # var_dump( $channel ); 

        $parser = new \Onion\Pear\ChannelParser;
        ok( $parser );


        $files = explode(' ','tests/data/pear2.channel.xml tests/data/pear.channel.xml tests/data/pear2.channel.xml');

        foreach( $files as $file ) {
            $channel = $parser->parse( $file );
            ok( $channel );
            ok($channel->name);
            ok($channel->summary);
            ok($channel->alias);
            ok($channel->primary);
        }
    }
}

