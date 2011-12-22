<?php
/*
 * This file is part of the Onion package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Onion;
use Exception;


/**
 * ini config container provides an interface for retrive strucutre config value
 *
 *  [section]
 *  name = value
 *
 *  $config->get('section.name');
 *
 *
 *  [section]
 *  name.subname = value
 *
 *  $config->get('section.name.subname')
 *
 */
class ConfigContainer 
{
    public $array;

    function __construct($array)
    {
        $this->array = $array;
    }

    function __get($k)
    {
        return $this->get( $k );
    }

    function __set($k,$v)
    {
        $this->set( $k , $v );
    }

    function __isset($k)
    {
        return $this->has($k);
    }

    function has( $refstr ) 
    {
        $paths = explode('.',$refstr,2);
        $ref = & $this->array;
        foreach( $paths as $p ) {
            if( ! isset( $ref[ $p ] ) )
                return false;
            $ref = & $ref[$p];
        }
        return true;
    }

    function set( $refstr , $v ) 
    {
        $paths = explode('.',$refstr,2);
        $ref = & $this->array;
        foreach( $paths as $p ) {
            if( ! isset( $ref[ $p ] ) ) {
                $ref[ $p ] = array();
                $ref = & $ref[$p];
            }
            else {
                $ref = & $ref[$p];
            }
        }
        $ref = $v;
    }

    function get( $refstr )
    {
        $paths = explode('.',$refstr,2);
        $ref = $this->array;
        foreach( $paths as $path ) {
            if( isset($ref[$path]) ) {
                $ref = & $ref[$path];
            } else {
                // debug_print_backtrace();
                throw new Exception("config key $refstr is undefined.");
            }
        }
        return $ref;
    }
}
