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
use CLIFramework\Logger;


// XXX: abandond

class CommandContext 
{
    public $argv;
    public $script;
    public $arguments;
    public $logger;

    /* for saving parsed option result */
    public $options;

    function __construct($argv = array() )
    {
        $this->argv         = array_merge( array(), $argv);
        $this->script       = $argv[0];
        $this->arguments    = array_slice($argv,1); // argument queue, not for option parsing
        $this->logger       = new Logger;
    }
}
