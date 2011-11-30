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

    function testSubcommand()
    {
        /*
        $context = new \CLIFramework\CommandContext(array('script','help','subcommand','--option','value','-a','-b'));
        $this->assertNotEmpty( $context );
        $this->assertTrue( $context->hasSubcommand() );

        $cmd = $context->shiftArgument();
        $this->assertEquals( 'help' , $cmd );

        $cmd = $context->shiftArgument();
        $this->assertEquals( 'subcommand' , $cmd );

        $this->assertNotEmpty( $context->script );
        */
    }

    function testNoSubcommand()
    {
        /*
        $context = new \CLIFramework\CommandContext(array('script','--option','value','-a','-b'));
        $this->assertNotEmpty( $context );
        $this->assertFalse( $context->hasSubcommand() );
        */
    }


}
