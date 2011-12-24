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
namespace Onion\Installer;
use Phar;

// xxx: use logger to parse

class PearInstaller 
    implements InstallerInterface
{

    public $basepath = 'pear';

    function install( $package ) 
    {
        $logger = \Onion\Application::getLogger();

        $logger->info( "Installing {$package->name}" );

        // create temp dir
        // (PHP 5 >= 5.2.1)
        $tmpDir = sys_get_temp_dir();
        $installTmpDir = $tmpDir . DIRECTORY_SEPARATOR . 'onion' . DIRECTORY_SEPARATOR . '.work' . DIRECTORY_SEPARATOR . time() ;

        if( !  file_exists($installTmpDir) )
            mkdir( $installTmpDir , 0755, true );

        $cwd = getcwd();

        $logger->info("chdir $installTmpDir");
        chdir( $installTmpDir );

        // var_dump( $package ); 
        $url = $package->getDistUrlByVersion( $package->latest );
        var_dump( $url ); 

        $info = parse_url( $url );

        // download the package.
        $logger->info( "Downloading " . $package->getId() . '-' . $package->latest . "..." );
        system( "curl -\# -O $url" );

        var_dump( $info ); 

        $file = basename($info['path']);
        $archive = new \PharData($file);

        $logger->info( "Extracting ..." );
        $archive->extractTo( $package->name );

        // parse package.xml
        $xmlstr = file_get_contents( $package->name . DIRECTORY_SEPARATOR . 'package.xml' );
        $xml = new DOMDocument( $xmlstr );

        // build file list, separate by roles

        chdir( $cwd );
    }
}

