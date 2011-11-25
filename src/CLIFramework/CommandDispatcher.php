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
    /* argv context class */
    public $context;

    /* command laoder */
    public $loader;


    public function __construct($app_command_namespaces = array() ,$argv = null)
    {
        if( $argv )  {
            $this->context = is_a($argv,'CLIFramework\CommandContext') ? $argv : new CommandContext($argv);
        } else {
            global $argv;
            $this->context = new CommandContext($argv);
        }

        $loader = $this->loader = new CommandLoader;
        $loader->addNamespace( (array) $app_command_namespaces );

        // push default command namespace into the list.
        $loader->addNamespace( '\\CLIFramework\\Command' );

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
        // translate command name to class name
        $subclass = $this->translateCommandClassName( $command );
        $class =  $this->loader->load( $subclass );
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
            return $this->dispatch('help');  # help command class.
        }
    }

    public function shiftDispatch($parent)
    {
        $subcommand = $this->context->getNextArgument();

        // get parent command namespace
        $parent_ns = get_class($parent);
        $parts = explode('\\',$parent_ns);
        $parent_class = end($parts);

        // get subcommand classname
        $subclass = $this->translateCommandClassName($subcommand);
        $subclass = $parent_class . '\\' .  $subclass;

        // if there is no such command class, then it should be an argument.
        $class = $this->loader->load( $subclass );  
        if( !$class)
            throw new Exception( "Sub command '$subcommand' not found." );

        $this->context->shiftArgument();

        // re-dispatch context to subcommand class.
        $cmd = new $class($this);
        return $cmd->topExecute($this->context);
    }
}

