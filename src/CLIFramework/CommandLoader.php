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

class CommandLoader 
{
    public $namespaces = array();

    public function addNamespace( $ns )
    {
        $nss = (array) $ns;
        foreach( $nss as $n )
            $this->namespaces[] = $n;
    }


    /* load command class:
     *
     * @param string $subclass Command class name
     * @return boolean
     **/
    public function load($subclass)
    {
        // has application command class ?
        foreach( $this->namespaces as $ns ) {
            $class = $ns . '\\' . $subclass;
            if( class_exists($class) )
                return $class;
            else
                spl_autoload_call( $class );
            if( class_exists($class) )
                return $class;
        }
    }

}


