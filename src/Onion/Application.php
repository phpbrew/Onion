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

    function brief()
    {
        return 'Onion - PHP Package builder.';
    }

    function options($specs)
    {

    }

    function init()
    {
        $this->registerCommand( 'init' );
        $this->registerCommand( 'build' );
        parent::init();
    }
}
