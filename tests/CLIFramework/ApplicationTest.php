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
namespace tests\CLIFramework;

use CLIFramework\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    function test()
    {
        $app = new Application;
        ok( $app );

        $app->registerCommand( 'list' , 'CLIFramework\\Commands\\ListCommand' );
        $app->registerCommand( 'help' , 'CLIFramework\\Commands\\HelpCommand' );

        $dispatcher = new \CLIFramework\CommandDispatcher( '\\Onion\\Command' );
        $dispatcher->loader->load('build');
        $dispatcher->loader->load('init');
        $ret = $dispatcher->runDispatch();


    }
}
