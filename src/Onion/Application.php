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

class Application extends \CLIFramework\Application
{
    const app_name = 'Onion';
    const app_version = '0.0.13';

    function brief()
    {
        return 'Onion - PHP Package builder.';
    }

    function options($opts)
    {
        parent::options($opts);
    }

    function init()
    {
        parent::init();
        $this->registerCommand( 'init' );
        $this->registerCommand( 'build' );
        $this->registerCommand( 'compile' );
        $this->registerCommand( 'bundle' );
    }


}
