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

namespace Onion\Command;
use CLIFramework\Command;
use Onion\Dependency\DependencyResolver;
use Onion\Installer;

/**
 * Bundle/Install dependencies
 */
class InstallCommand extends Command
{

    function brief()
    {
        return 'Install dependencies into current vendor path';
    }

    function options($opts)
    {
        $opts->add('b|base','base directory path');
    }


    /**
     * pecl installer steps
     *
     * wget http://pecl.php.net/get/bcompiler-1.0.2.tgz
     * tar xvf bcompiler-1.0.2.tgz
     * cd bcompiler-1.0.2
     * phpize
     * ./configure
     * make
     * make INSTALL_ROOT=/var/tmp/tmp_root install
     */

    function execute()
    {
        $logger = $this->getLogger();

        // convert package.ini to package.xml
        if( ! file_exists('package.ini') ) {
            $logger->error('package.ini not found, please define one.');
            return false;
        }

        $reader = new \Onion\PackageConfigReader;
        $reader->setLogger( $logger );

        $pkg = $reader->read( 'package.ini' );
        $pkg->local = 1; // dont install this

        $dr = new DependencyResolver;
        $dr->resolve( $pkg );

        $pool = $dr->getPool();
        // $packages = $pool->getPackages();

        $installer = new Installer( $pool );
        $installer->install();
        $logger->info('Done');
    }
}
