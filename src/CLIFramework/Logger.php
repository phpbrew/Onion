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
use CLIFramework\Formatter;

class Logger 
{

    /*
     * log level
     *
     * critical error = 1
     * error          = 2
     * warn           = 3
     * info           = 4
     * info2          = 5
     * debug          = 6
     * debug2         = 7
     *
     * */
    public $level = 7;
	public $formatter;

	public function __construct()
	{
		$this->formatter = new Formatter;
	}

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function criticalError($msg)
    {
        echo $this->formatter->format( $msg , 'error' ) . "\n";
    }

    public function error($msg)
    {
        echo $this->formatter->format( $msg , 'error2' ) . "\n";
    }

    public function warn($msg)
    {
        echo $this->formatter->format( $msg , 'warn' ) . "\n";
    }

    public function info($msg,$style = 'info' )
    {
        echo $this->formatter->format( $msg , $style ) . "\n";
    }

    public function info2($msg) 
    {
        $this->info($msg,'info2');
    }

    public function debug($msg, $style = 'debug' )
    {
        /* detect object */
        if( is_object($msg) || is_array($msg) )  {
            echo $this->formatter->format( print_r( $msg , 1 ) ) . "\n";
        } else {
            echo $this->formatter->format( $msg , $style ) , "\n";
        }
    }

    public function debug2($msg)
    {
        $this->debug($msg,'debug2');
    }
}




