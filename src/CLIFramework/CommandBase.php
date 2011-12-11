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



/**
 * Command based class
 *
 * register subcommands.
 */
abstract class CommandBase 
{

    /* application commands */
    public $commands = array();

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

    /* 
     * sub command override this method to define its option spec here 
     *
     * it's spec collection object.
     * */
    function options($getopt)
    {

    }

    /**
     * init function 
     *
     * register subcommand here
     * */
    function init()
    {

    }



    /**
     * register command to application
     *
     * XXX: support optional class name, auto translate command name into class 
     * name.
     *
     * class name could be full-qualified or subclass name (under App\Command\ )
     */
    public function registerCommand($command,$class)
    {
        // try to load the class/subclass.
        if( $this->loader->loadClass( $class ) === false )
            throw Exception("Command class not found.");
        $this->commands[ $command ] = $class;
    }


    /**
     * return command name list
     *
     * @return Array
     */
    public function getCommandList()
    {
        return array_keys( $this->commands );
    }


    /*
     * return the command class name
     *
     */
    public function getCommandClass($command)
    {
        return @$this->commands[ $command ];
    }

    /* main command execute method */
    abstract function execute($arguments);
}



