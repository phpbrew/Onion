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

/**
 * Bundle dependencies
 */
class BundleCommand extends Command
{

    function brief()
    {
        return 'use pear to install dependencies into current .local path';
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

    function execute($arguments)
    {
		$logger = $this->getLogger();

        // convert package.ini to package.xml
		if( ! file_exists('package.ini') ) {
			$logger->error('package.ini not found, please define one.');
			return false;
		}


        // $cmd = $this->application->getCommand('build');
        // $cmd->execute(array());

        // init pear dependency manager

        // read current package.xml file

        // lookup dependency

        // discover required channels

        // for each required packages
        //  - use downloader to download it.
        //  - unpack, extract file
        //  - read package.xml and install it into .local directory.


    }

}
