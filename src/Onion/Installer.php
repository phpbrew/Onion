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
 * main installer
 */
class Installer 
{
    public $manager;
    public $workspace;
    public $libpath;

    /**
     * xxx: we should expand operations from manager,
     * installer should take operation object to process, not from manager class.
     */
    function __construct( \Onion\Dependency\DependencyManager $manager)
    {
        $this->manager = $manager;

        // create workspace for temporary files
        $workspace = $this->workspace = '.onion' . DIRECTORY_SEPARATOR . 'workspaces' . DIRECTORY_SEPARATOR . time();
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
        $packages = $this->manager->getPackages();
        foreach( $packages as $package ) {
            $installer = $this->getPearInstaller();
            $installer->install( $package );
        }
    }
}


