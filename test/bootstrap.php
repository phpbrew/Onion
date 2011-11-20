<?php
class Autoloader 
{
	public $basedir;

	function loadclass($class)
	{
		$path = $this->basedir . DIRECTORY_SEPARATOR .  str_replace('\\',DIRECTORY_SEPARATOR,$class). '.php';
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
