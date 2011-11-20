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
use Onion\ConfigFile;

class GlobalConfig  extends ConfigFile
{
    function __construct()
    {
        $home = getenv('HOME');
        $file = $home . DIRECTORY_SEPARATOR . '.onion.ini';  # ~/.onion.ini
        parent::__construct($file);
    }

    function defaultContent()
    {
        $default = <<<CONFIG
[author]
name = Your Name
email = your@email
CONFIG;
        return $default;
    }


}



