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

class CommandContext 
{
    public $argv;
    public $script;
    public $context_list;

    function __construct($argv = array() )
    {
        $this->argv         = array_merge( array(), $argv);
        $this->script       = $argv[0];
        $this->context_list = array_slice($argv,1); # copy and shift one
    }

    function shiftArgument()
    {
        return array_shift($this->context_list);
    }

    function getNextArgument()
    {
        return @$this->context_list[0];
    }

    function getRestArguments()
    {
        return $this->context_list;
    }

    function hasSubcommand()
    {
        // make sure we have command class for this subcommand.
        $next = $this->getNextArgument();
        return $next && (strpos($next,'-') !== 0);
    }

    function hasCommand()
    {
        return count($this->context_list) == 0;
    }

}
