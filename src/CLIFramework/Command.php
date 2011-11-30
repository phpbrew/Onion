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

class Command
{
    public $dispatcher;
    public $cascade;

    function __construct($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }


    /* sub command override this method to define its option spec here */
    function options($getopt)
    {

    }

    /* get options from current context */
    function getOptions($context)
    {
        $getopt = new GetOptionKit;

        // init defined options.
        $this->options($getopt);

        // parse arguments from the argument queue.
        $options = $getopt->parse( $context->arguments );

        // save option result in command context object.
        return $context->options = $options;
    }



    /* this is for parent command and subcommands */
    function topExecute($context)
    {
        $this->prepare();
        $ret = null;

        if( $this->cascade ) {

            // if we have sub-command (not start with dashes), run it
            if( $context->hasSubcommand() ) {
                $ret = $this->dispatcher->shiftDispatch($this);
            } else {
                // if not, and sub-commands is defined. list it.
                throw new Exception;
            }
        } else {
            $ret = $this->execute($context);
        }

        if( $ret ) {

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
    function brief() 
    {
        return 'undefined.';
    }

    function toCommandName()
    {
        $class = get_class($this);
        $class = preg_replace( '/Command$/','', $class );
        $parts = explode('\\',$class);
        $class = end($parts);
        return strtolower( preg_replace( '/(?<=[a-z])([A-Z])/', '-\1' , $class ) );
    }

}
