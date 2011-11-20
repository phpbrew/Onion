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
    function execute($context) 
    {
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

        $content[] = '[package]';
        $content[] = 'name = ';
        $content[] = 'version = ';
        $content[] = 'desc = ';
        $content[] = 'author = ' . $author['name'] . '<' . $author['email'] . '>';

        if( file_exists('package.ini') )
            throw new Exception('package.ini exists. aborting.');
        file_put_contents('package.ini' , join("\n",$content) );
    }
}
