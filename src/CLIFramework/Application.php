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
use CLIFramework\CommandDispatcher;
use CLIFramework\CommandLoader;

class Application 
{
    /* application subcommands */
    public $subcommands = array();

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

        $this->subcommands[] = array(
            'command' => $commmand,
            'class'   => $class,
        );
    }

    public function getCommandList()
    {
        return array_map( function($item) { return $item['command']; } , $this->subcommands );
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




        while( ! $getopt->isEnd() ) {
            if( $getopt->getCurrentArgument() == $subcommands[0] ) {
                $getopt->advance();
                $subcommand = array_shift( $subcommands );
                $getopt->setOptions( $subcommand_specs[$subcommand] );
                $subcommand_options[ $subcommand ] = $getopt->continueParse();
            } else {
                $arguments[] = $getopt->advance();
            }
        }

        // $ret = $dispatcher->runDispatch();
    }

}

