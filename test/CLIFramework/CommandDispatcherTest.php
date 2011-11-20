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
        $dispatcher = new \CLIFramework\CommandDispatcher( '\\Onion\\Command', array( 'script' , 'help' ) );
        $ret = $dispatcher->runDispatch();
        $this->assertTrue( $ret );
    }

    function test_dispatch()
    {
        $dispatcher = new \CLIFramework\CommandDispatcher( '\\Onion\\Command', array( 'script' , 'help' ) );
        $ret = $dispatcher->dispatch('help');
        $this->assertTrue( $ret );
    }

    function test_subcommand()
    {
        $dispatcher = new \CLIFramework\CommandDispatcher( '\\Onion\\TestCommand' , array( 'script' , 'help' , 'subcommand' ) );
        $ret = $dispatcher->dispatch('help');
        $this->assertTrue( $ret );
    }

}
