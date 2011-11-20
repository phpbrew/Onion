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
use CLIFramework\CommandInterface;
use Onion\GlobalConfig;
use Onion\PackageConfigFile;

class BuildCommand extends Command 
    implements CommandInterface
{
    function execute($context) 
    {
        $config = new \Onion\GlobalConfig;
        if( ! $config->exists() ) {
            // create an skeleton for user and exit.
            $default = $config->defaultContent();
            file_put_contents( $config_path , $default );
            echo "please edit your config file first.\n";
            echo "   \$ vim $config_path\n";
            return true;
        }
        $config->read();

        $author = $config->author;
        if( empty($author) ) {
            echo "[author] section is not defined.";
            return false;
        }

        echo "Configuring package.ini...\n";
        $config = new PackageConfigFile('package.ini');
        $config->validate();
        $config->buildPearConfig();
        return true;
    }
}

