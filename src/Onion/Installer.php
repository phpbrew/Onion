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


/**
 * Main installer
 */
class Installer 
{
    public $pool;
    public $workspace;
    public $libpath;

    /**
     * XXX: we should expand operations from pool,
     * installer should take operation object to process, not from dependency pool class.
     *
     * @param DependencyPool $pool
     * @param array $options
     */
    function __construct($pool, $options = array() )
    {
        $this->pool = $pool;

        // create workspace for temporary files
        if( isset($options['workspace']) ) {
            $workspace = $options['workspace'];
        } else {
            $workspace = $this->workspace = '.onion' . DIRECTORY_SEPARATOR . 'workspaces' . DIRECTORY_SEPARATOR . time();
        }

        if( !  file_exists($workspace) )
            mkdir( $workspace , 0755, true );

        $this->libpath = 'vendor';
    }

    function getWorkspace()
    {
        return $this->workspace;
    }

    function getLibraryInstaller()
    {
        return new Installer\LibraryInstaller( $this );
    }

    function getPearInstaller()
    {
        return new Installer\PearInstaller( $this );
    }

    function install()
    {
        $packages = $this->pool->getPackages();
        foreach( $packages as $package ) {
            $installer = $this->getPearInstaller();
            $installer->install( $package );
        }
    }
}


