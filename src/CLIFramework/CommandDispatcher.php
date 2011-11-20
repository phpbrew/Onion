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
use Exception;

class CommandDispatcher 
{
    /* argv context class */
    public $context;

    /* application command namespace, somewhere you put command classes together
     *   like   App\Command\......Command */
    public $app_command_namespace;

    public function __construct($app_command_namespace,$argv = null)
    {
        if( ! $argv )  {
            global $argv;
            $this->context = new CommandContext($argv);
        } else {
            $this->context = new CommandContext($argv);
        }
        $this->app_command_namespace = $app_command_namespace;
    }

    public function shiftDispatch()
    {

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

        // has application command class ?
        $class = $this->app_command_namespace . '\\' . $subclass;
        if( ! class_exists($class) )
            spl_autoload_call( $class );
        if( class_exists($class) )
            return $class;

        // built-in command.
        $class = '\\CLIFramework\\Command\\' . $subclass;
        if( ! class_exists($class) )
            spl_autoload_call( $class );
        if( class_exists($class) )
            return $class;

        throw new Exception( "Command '$command' not found." );
    }

    function runDispatch()
    {
        $command = $this->context->getNextArgument();
        return $this->dispatch($command);
    }

    function dispatch( $command = null )
    {
        if( $command ) 
        {
            $class = $this->getCommandClass($command);
            $cmd = new $class($this);
            $cmd->execute($context);
        } 
        else 
        {
            $this->dispatch('help');  # help command class.
        }
        return true;
    }

}

