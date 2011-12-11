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
namespace CLIFramework;
use CLIFramework\CommandContext;
use CLIFramework\CommandLoader;
use Exception;

class CommandDispatcher 
{
    /* argv context class, 
     * contains arguments, options, and argument queue. */
    public $context;

    public function translateCommandClassName($command)
    {
        $args = explode('-',$command);
        foreach($args as & $a)
            $a = ucfirst($a);
        $subclass = join('',$args) . 'Command';
        return $subclass;
    }

    public function getCommandClass($command)
    {
        $class =  $this->loader->load( $command );
        if( !$class)
            throw new Exception( "Command '$command' not found." );
        return $class;
    }

    public function getSubcommandClass($subcommand)
    {
        return $this->loader->loadSubcommand( $subcommand );
    }

    public function hasSubcommand($subcommand)
    {
        return $this->getSubcommandClass($subcommand) ? true : false;
    }

}

