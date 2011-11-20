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

class Command
{
    public $dispatcher;

    function __construct($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /* this is for parent command and subcommands */
    function topExecute($context)
    {
        $this->prepare();
        $ret = null;
        if( $context->hasSubcommand() ) {
            $ret = $this->dispatcher->shiftDispatch($this);
        } else {
            $ret = $this->execute($context);
        }

        if( $ret ) {
            // if we have sub-commands, run it

            // if not, and sub-commands is defined. list it.
        }
        $this->finish();
        return $ret;
    }

    function execute($context)
    {
        return false;
    }

    function prepare() { }
    function finish() { }

    /* TODO: read brief from markdown format doc file. */
    function brief() { return 'undefined.'; }

    function toCommandName()
    {
        $class = get_class($this);
        $class = preg_replace( '/Command$/','', $class );
        $parts = explode('\\',$class);
        $class = end($parts);
        return strtolower( preg_replace( '/(?<=[a-z])([A-Z])/', '-\1' , $class ) );
    }

}
