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
namespace TestApp;

class Application extends \CLIFramework\Application 
{

    function options($specs)
    {
        $specs->addFromSpecString('v|verbose','Verbose message');
        $specs->addFromSpecString('d|debug','Debug message');
        $specs->addFromSpecString('c|color','Color message');
    }

    function init()
    {
        // parent::init();
        $this->registerCommand('list');
    }

}
