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

abstract class Command
{
    public $options;

    function usage()
    {
        // return usage
    }

    /* TODO: read brief from markdown format doc file. */
    function brief() 
    {
        return 'undefined.';
    }

    /* sub command override this method to define its option spec here */
    function options($getopt)
    {

    }


    /* main command execute method */
    abstract function execute($arguments);

    /* prepare stage */
    function prepare() { }

    /* for finalize stage */
    function finish() { }


    function getOptions()
    {
        return $this->options;
    }

    function getCommandName()
    {
        $class = get_class($this);
        $class = preg_replace( '/Command$/','', $class );
        $parts = explode('\\',$class);
        $class = end($parts);
        return strtolower( preg_replace( '/(?<=[a-z])([A-Z])/', '-\1' , $class ) );
    }


}
