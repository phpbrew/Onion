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
namespace CLIFramework\Command;
use CLIFramework\Command;
use CLIFramework\CommandInterface;

class HelpCommand extends Command
    implements CommandInterface
{

    function execute($context)
    {
        // get command list, command classes should be preloaded.
        $classes = get_declared_classes();
        $command_classes = array();
        foreach( $classes as $class ) {
            if( is_a($class,'CLIFramework\Command') ) 
                $command_classes[] = $class;
        }

        // print command brief list
        foreach( $command_classes as $class ) {
            $cmd = new $class($this->dispatcher);
            $brief = $cmd->brief();
        }

        // if empty command list
        $file =  __FILE__ . '.md';
        if( file_exists( $file ) )
            echo file_get_contents( $file );
        return true;
    }

}