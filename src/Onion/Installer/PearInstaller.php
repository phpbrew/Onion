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



/**
 *
 *
 *
 */
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
        $packageDir =  $workspace . DIRECTORY_SEPARATOR . $package->name;
        $packageSourceDir = $packageDir . DIRECTORY_SEPARATOR . $package->name . '-' . $package->latest;

        $url = $package->getReleaseDistUrl( $package->latest );
        $info = parse_url( $url );

        // download the package.
        $logger->info( "Downloading " . $package->name . '-' . $package->latest . "..." );

        $cwd = getcwd();
        chdir( $workspace );

        $dm = new \Onion\Downloader\DownloaderManager;
        $downloader = $dm->createDownloader($logger->level == 2);
        $content = $downloader->request($url);

        // store file
        file_put_contents( basename($info['path']) , $content );

        chdir( $cwd );

        $sourceFile = $workspace . DIRECTORY_SEPARATOR . basename($info['path']);
        $archive = new \PharData($sourceFile);

        $logger->info( "Extracting ..." );
        $archive->extractTo( $packageDir );


        $pearLibPath = $this->mainInstaller->libpath . DIRECTORY_SEPARATOR . $this->basepath;
        $logger->debug2( "Install to $pearLibPath" );

        if( ! file_exists($pearLibPath) )
            mkdir( $pearLibPath , 0755, true );


        // parse package.xml
        $parser = new \Onion\Pear\PackageXmlParser( $packageDir . DIRECTORY_SEPARATOR . 'package.xml' );

        // build file list, separate by roles
        $contentFiles = $parser->getContentFiles();
        $installFilelist = (array) $parser->getPhpReleaseFileList();
        
        $installMap = array();
        foreach( $installFilelist as $install ) {
            // var_dump( $install ); 
            $installMap[ $install->file ] = $install->as;
        }

        // install files into it
        foreach( $contentFiles as $file ) {
            // install php code only (for now)
            if( $file->role == 'php' ) {
                $installFrom = $packageSourceDir . DIRECTORY_SEPARATOR . $file->file;
                $installTo = $pearLibPath . DIRECTORY_SEPARATOR . $file->getInstallAs();
                if( isset($installMap[ $file->file ]) ) {
                    $as = $installMap[ $file->file ];
                    $installTo = $pearLibPath . DIRECTORY_SEPARATOR . $as;
                }

                $dir = dirname( $installTo );
                if( ! file_exists( $dir ) )
                    mkdir( $dir , 0755 , true );

                $logger->info2( "Install $installTo" ,1 );
                copy( $installFrom , $installTo );
            }
        }
    }
}

