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
namespace CLIFramework;

class CommandDispatcherTest extends \PHPUnit_Framework_TestCase
{

    function test_runDispatch()
    {
        $context = new \CLIFramework\CommandContext( array( 'script' , 'help' ));
        $dispatcher = new \CLIFramework\CommandDispatcher( '\\Onion\\Command', $context );
        $ret = $dispatcher->runDispatch();
        $this->assertTrue( $ret );
    }

    function test_dispatch()
    {
        $context = new \CLIFramework\CommandContext( array( 'script' , 'help' ));
        $dispatcher = new \CLIFramework\CommandDispatcher( '\\Onion\\Command', $context );
        $ret = $dispatcher->runDispatch();
        $this->assertTrue( $ret );
    }

    function test_subcommand()
    {
        $context = new \CLIFramework\CommandContext( array( 'script' , 'help' ));
        $dispatcher = new \CLIFramework\CommandDispatcher( '\\Onion\\TestCommand' , $context );
        $ret = $dispatcher->runDispatch();
        $this->assertTrue( $ret );
    }

    function test_command()
    {
        $argv = array( 'script' , 'parent' , 'sub' );
        $dispatcher = new \CLIFramework\CommandDispatcher( '\\Onion\\TestCommand' , $context );

        $cmd = new \Onion\TestCommand\ParentCommand( $dispatcher );
        $this->assertEquals( 'parent', $cmd->toCommandName() );
        $cmd->execute( $dispatcher->context );

        ob_flush();
    }

}
