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


    /* translate command name to class name */
    public function translate($command)
    {
        $args = explode('-',$command);
        foreach($args as & $a)
            $a = ucfirst($a);
        return join('',$args) . 'Command';
    }

    /* load command class:
     *
     * @param string $command command name
     * @return boolean
     **/
    public function load($command)
    {
        $subclass = $this->translate($command);
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

    /* load subcommand class
     *
     * @param $command
     * @param $parent parent command class
     *
     * */
    public function loadSubcommand($command,$parent)
    {
        // get parent command namespace
        $parent_ns = get_class($parent);
        $parts     = explode('\\',$parent_ns);
        $parent_class = end($parts);

        // get subcommand classname
        $class = $parent_class . '\\' . $this->translate($subcommand);
        return $this->load($class);
    }

}


