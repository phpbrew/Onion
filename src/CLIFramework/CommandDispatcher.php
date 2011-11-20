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
    public $app_command_namespaces;

    public function __construct($app_command_namespaces = array() ,$argv = null)
    {
        if( ! $argv )  {
            global $argv;
            $this->context = new CommandContext($argv);
        } else {
            $this->context = new CommandContext($argv);
        }

        // push default command namespace into the list.
        $app_command_namespaces = (array) $app_command_namespaces;
        $app_command_namespaces[] = '\\CLIFramework\\Command';
        $this->app_command_namespaces = $app_command_namespaces;
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
        foreach( $this->app_command_namespaces as $ns ) {
            $class = $ns . '\\' . $subclass;
            if( ! class_exists($class) )
                spl_autoload_call( $class );
            if( class_exists($class) )
                return $class;
        }


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
            $cmd->topExecute($context);
        } 
        else 
        {
            $this->dispatch('help');  # help command class.
        }
        return true;
    }

}

