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
use GetOptionKit\GetOptionKit;

class CommandContext 
{
    public $argv;
    public $script;
    public $arguments;
    public $getopt;
    public $getoptResult;

    function __construct($argv = array() )
    {
        $this->argv         = array_merge( array(), $argv);
        $this->script       = $argv[0];
        $getopt             = new GetOptionKit;
        $result             = $getopt->parse( $argv );
        
        // $this->arguments = array_slice($argv,1); # copy and shift one
        $this->getopt       = $getopt;
        $this->getoptResult = $result;
        $this->arguments    = array_merge(array(),$result->arguments);
    }

    function shiftArgument()
    {
        return array_shift($this->arguments);
    }

    function getNextArgument()
    {
        return @$this->arguments[0];
    }

    function getRestArguments()
    {
        return $this->arguments;
    }

    function hasSubcommand()
    {
        // make sure we have command class for this subcommand.
        $next = $this->getNextArgument();
        return $next && (strpos($next,'-') !== 0);
    }

    function hasCommand()
    {
        return count($this->arguments) == 0;
    }

    function parseOptions()
    {

    }

}
