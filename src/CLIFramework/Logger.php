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
	public $formatter;

	public function __construct()
	{
		$this->formatter = new Formatter;
	}

    public function info( $msg , $bold = 0 )
    {
        echo $this->formatter->format( $msg , 'info' ) . "\n";
    }

    public function debug($msg)
    {
        /* detect object */
        if( is_object($msg) || is_array($msg) )  {
            var_dump( $msg );
        } else {
            echo $this->formatter->format( $msg , 'debug' ) , "\n";
        }
    }

}




