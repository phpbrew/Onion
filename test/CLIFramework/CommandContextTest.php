<?php
/*
 * This file is part of the CLIFramework package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
class CommandContextTest extends PHPUnit_Framework_TestCase
{



    function testShift()
    {
        $context = new \CLIFramework\CommandContext(array('script','help','subcommand','--option','value'));
        $this->assertNotEmpty( $context );
        $cmd = $context->shiftArgument();
        $this->assertEquals( 'help' , $cmd );

        $cmd = $context->shiftArgument();
        $this->assertEquals( 'subcommand' , $cmd );
    }
}
