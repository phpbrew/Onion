<?php
/*
 * This file is part of the {{ }} package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace CLIFramework;
use GetOptionKit\ContinuousOptionParser;
use GetOptionKit\OptionSpecCollection;
use CLIFramework\CommandDispatcher;
use CLIFramework\CommandLoader;

class Application 
{
    /* application commands */
    public $commands = array();

    // command dispatcher
    public $dispatcher;

    // command class loader
    public $loader;

    // options parser
    public $optionParser;

    // command namespace for autoloader
    public $commandNamespaces = array( 
        '\\Onion\\Command',
        '\\CLIFramework\\Command'
    );


    public $applicationOptions;



    function __construct()
    {
        // default command base class
        $this->dispatcher = new CommandDispatcher();

        $this->loader = new CommandLoader();
        $this->loader->addNamespace( $this->commandNamespaces );

        $this->optionsParser = new ContinuousOptionParser;

        // $dispatcher->loader->load('build');
        // $dispatcher->loader->load('init');
    }


    /**
     * register application option specs to the parser
     */
    public function options(ContinuousOptionParser $parser)
    {
        // $parser->add( );

    }

    /**
     * register command to application
     */
    public function registerCommand($command,$class)
    {
        // try to load the class/subclass.
        if( $this->loader->loadClass( $class ) === false )
            throw Exception("Command class not found.");

        $this->commands[] = array(
            'command' => $commmand,
            'class'   => $class,
        );
    }

    public function getCommandList()
    {
        return array_map( function($item) { return $item['command']; } , $this->commands );
    }




    /* 
     * init application,
     *
     * users register command mapping here. (command to class name)
     */
    public function init()
    {

    }


    /**
     * run application with 
     * list argv 
     *
     * @param Array $argv
     *
     * */
    public function run(Array $argv)
    {
        // init application 
        $this->init();

        // use getoption kit to parse application options
        $getopt = $this->optionsParser;
        $this->options($getopt);
        $this->applicationOptions = $getopt->parse( $argv );

        $command_stack = array();
        $command_list = $this->getCommandList();
        $arguments = array();
        $cmd = null;

        while( ! $getopt->isEnd() ) {
            if( in_array(  $getopt->getCurrentArgument() , $command_list ) ) {
                $getopt->advance();
                $subcommand = array_shift( $command_list );

                // initialize subcommand (subcommand with parent command class)
                if( end($command_stack) ) {
                    $cmd = $this->loader->loadSubcommand($subcommand, end($command_stack));
                } 
                else {
                    $cmd = $this->loader->load( $subcommand );
                }

                // init subcommand option, XXX: reset option specs
                $getopt->setOptions( new OptionSpecCollection );
                $cmd->options( $getopt );

                // register subcommands
                $cmd->init();

                // parse options for command.
                $cmd_options = $getopt->continueParse();

                // run subcommand prepare
                $cmd->prepare();

                $cmd->options = $cmd_options;
                $command_stack[] = $cmd; // save command object into the stack

            } else {
                $arguments[] = $getopt->advance();
            }
        }

        // get last command and run
        if( $last_cmd = array_pop( $command_list ) ) {
            $last_cmd->execute( $arguments );
            while( $cmd = array_pop( $command_list ) ) {
                // call finish stage.. of every command.
                $cmd->finish();
            }
        }
        else {
            // no command specified.
            $this->execute( $arguments );
        }
    }


    public function execute( $arguments )
    {
        // show list and help,
    }


}

