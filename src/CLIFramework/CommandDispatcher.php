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

    public function __construct($argv = null)
    {

        if( $argv )  {
            $this->context = is_a($argv,'CLIFramework\CommandContext') ? $argv : new CommandContext($argv);
        } else {
            global $argv;
            $this->context = new CommandContext($argv);
        }
    }

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

    public function runDispatch()
    {
        $command = $this->context->shiftArgument();
        return $this->dispatch($command);
    }


    /* dispatch comamnd , the entry point */
    public function dispatch( $command = null )
    {
        if( $command ) 
        {
            $class = $this->getCommandClass($command);
            $cmd = new $class($this);
            return $cmd->topExecute($this->context);
        } 
        else 
        {
            return $this->dispatch('help');  # dispatch to help command class.
        }
    }

    public function getSubcommandClass($subcommand)
    {
        return $this->loader->loadSubcommand( $subcommand );
    }

    public function hasSubcommand($subcommand)
    {
        return $this->getSubcommandClass($subcommand) ? true : false;
    }

    public function shiftDispatch($parent)
    {
        $subcommand = $this->context->getNextArgument();
        if( $class = $this->getSubcommandClass($subcommand) )  {
            $this->context->shiftArgument();

            // re-dispatch context to subcommand class.
            $cmd = new $class($this);
            return $cmd->topExecute($this->context);
        }
        else {
            throw new Exception( "Subcommand '$subcommand' not found." );
        }
    }

}

