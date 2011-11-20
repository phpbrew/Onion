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
use CLIFramework\CommandContext;

class CommandDispatcher 
{
    public $context;

    function __construct( $arg )
    {
        if( is_array( $arg ) ) {
            $this->context = new CommandContext($arg);
        }
        elseif( is_a( $arg, 'CLIFramework\CommandContext' ) ) {
            $this->context = $arg;
        }
    }

}

