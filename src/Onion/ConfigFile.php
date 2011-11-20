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
namespace Onion;

class ConfigFile
{
    public $config;
    public $file;

    function __construct($file)
    {
        $this->file = $file;
    }

    function exists()
    {
        return file_exists($this->file);
    }

    function read()
    {
        $this->config = parse_ini_file( $this->file , true );
    }

    function __isset($name)
    {
        return isset($this->config[$name]);
    }

    function __get($name)
    {
        return $this->config[$name];
    }

}
