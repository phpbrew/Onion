<?php
class Autoloader 
{
	public $basedir;

	function loadclass($class)
	{
		$path = $this->basedir . DIRECTORY_SEPARATOR .  str_replace('\\',DIRECTORY_SEPARATOR,$class). '.php';
        if( file_exists($path) )
            require $path;
	}

	function load()
	{
		# $this->basedir = dirname(dirname(__FILE__));
        $this->basedir = 'src';
		spl_autoload_register( array($this,'loadclass') );
	}
}

$loader = new Autoloader;
$loader->load();
