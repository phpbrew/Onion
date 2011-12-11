<?php
/*
 * This file is part of the {{ }} package.
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
use Exception;

class InitCommand extends Command 
    implements CommandInterface
{
    function brief()
    {
        return 'initialize package.ini file';
    }

    function execute($arguments) 
    {
        $logger = $this->logger;
        $logger->info('Checking package.ini');
        if( file_exists('package.ini') )
            throw new Exception('package.ini exists. aborting.');
        /*
        $config = new \Onion\GlobalConfig;
        if( ! $config->exists() ) {
            // create an skeleton for user and exit.
            $default = $config->defaultContent();
            file_put_contents( $config_path , $default );
            echo "Please edit your $config_path.\n";
            return true;
        }
        $config->read();
        $author = $config->author;
        if( empty($author) ) {
            echo "[author] section is not defined.";
            echo "Please edit your $config_path.\n";
            return false;
        }
        */
        $author = '';
        if( $a = getenv('ONION_AUTHOR') )
            $author = $a;
        $content[] = '[package]';
        $content[] = 'name = Your Package Name';
        $content[] = 'version = 0.0.1';
        $content[] = 'desc = Description Here';
        $content[] = 'author = ' . $author;
        file_put_contents('package.ini' , join("\n",$content) );
    }
}
