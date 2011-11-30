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
        $this->array[ $k ] = $v;
    }

    function get( $refstr )
    {
        $paths = explode('.',$refstr);
        $ref = $this->array;
        foreach( $paths as $path ) {
            if( isset($ref[$path]) ) {
                $ref = & $ref[$path];
            } else {
                throw new Exception("config key $refstr is undefined.");
            }
        }
        return $ref;
    }
}
