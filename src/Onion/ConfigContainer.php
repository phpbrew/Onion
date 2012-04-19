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
use ArrayAccess;
use IteratorAggregate;
use ArrayIterator;


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
    implements ArrayAccess, IteratorAggregate
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
                return null;
                // debug_print_backtrace();
                // throw new Exception("config key $refstr is undefined.");
            }
        }
        return $ref;
    }


    
    public function offsetSet($name,$value)
    {
        $this->array[ $name ] = $value;
    }
    
    public function offsetExists($name)
    {
        return isset($this->array[ $name ]);
    }
    
    public function offsetGet($name)
    {
        return $this->array[ $name ];
    }
    
    public function offsetUnset($name)
    {
        unset($this->array[$name]);
    }


    public function getIterator() 
    {
        return new ArrayIterator($this->array);
    }

    public function getRequires()
    {
        $requires = array();

        // parse require section



        // parse special require section
        foreach( $this->array as $key => $options ) {
            if( preg_match('/^require\s+"?(\w+)"?/i', $key, $regs ) ) {
                $name = $regs[1];
                $requires[ $name ]  = $options;
            }
        }
        return $requires;
    }

    public function getResources()
    {
        $resources = array();
        foreach( $this->array as $key => $options ) {
            if( preg_match('/^resource\s+"?(\w+)"?/i', $key, $regs ) ) {
                $name = $regs[1];
                $resources[ $name ]  = $options;
            }
        }
        return $resources;
    }

}
