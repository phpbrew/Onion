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
use Onion\Pear\PackageXmlParser;
use Phar;

// xxx: use logger to parse

class PearInstaller 
    implements InstallerInterface
{
    public $basepath = 'pear';
    public $mainInstaller;

    function __construct($main)
    {
        $this->mainInstaller = $main;
    }

    function install( $package ) 
    {
        $logger = \Onion\Application::getLogger();

        $logger->info( "Installing {$package->name}" );

        // create temp dir
        // (PHP 5 >= 5.2.1)
        // $tmpDir = sys_get_temp_dir();

        // $installTmpDir = $tmpDir . DIRECTORY_SEPARATOR . 'onion' . DIRECTORY_SEPARATOR . '.work' . DIRECTORY_SEPARATOR . time() ;
        $workspace = $this->mainInstaller->getWorkspace();
        $packageSourceDir =  $workspace . DIRECTORY_SEPARATOR . $package->name;

        // var_dump( $package ); 
        $url = $package->getDistUrlByVersion( $package->latest );

        // download the package.
        $logger->info( "Downloading " . $package->getId() . '-' . $package->latest . "..." );

        $cwd = getcwd();
        chdir( $workspace );
        system( "curl -O --progress-bar $url" );
        chdir( $cwd );


        $info = parse_url( $url );
        $sourceFile = $workspace . DIRECTORY_SEPARATOR . basename($info['path']);
        $archive = new \PharData($sourceFile);

        $logger->info( "Extracting ..." );
        $archive->extractTo( $packageSourceDir );


        $pearLibPath = $this->mainInstaller->libpath . DIRECTORY_SEPARATOR . $this->basepath;
        $logger->info( "Install to $pearLibPath" );

        if( ! file_exists($pearLibPath) )
            mkdir( $pearLibPath , 0755, true );


        // parse package.xml
        $parser = new \Onion\Pear\PackageXmlParser( $packageSourceDir . DIRECTORY_SEPARATOR . 'package.xml' );

        // build file list, separate by roles
        $contentFiles = $parser->getContentFiles();
        $installFilelist = (array) $parser->getPhpReleaseFileList();
        $installMap = array();
        foreach( $installFilelist as $install ) {
            $installMap[ $install->file ] = $install->as;
        }

        // install files into it
        foreach( $contentFiles as $file ) {
            // install php code only (for now)
            if( $file->role == 'php' ) {

            }
        }

    }
}

