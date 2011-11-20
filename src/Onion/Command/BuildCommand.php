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

class BuildCommand extends Command 
    implements CommandInterface
{
    function execute($context) 
    {
        $home = getenv('HOME');
        $config_path = $home . DIRECTORY_SEPARATOR . '.onion.ini';  # ~/.onion.ini
        if( ! file_exists( $config_path ) ) {
            // create an skeleton for user and exit.
            $default = <<<CONFIG
[author]
name = Your Name
email = your@email
CONFIG;
            file_put_contents( $config_path , $default );
            echo "please edit your config file first.\n";
            echo "   \$ vim $config_path\n";
            return true;
        }


        $author_config = parse_ini_file( $config_path );
        var_dump( $author_config ); 
        return true;
    }
}

