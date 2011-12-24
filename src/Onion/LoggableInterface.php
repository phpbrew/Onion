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

interface LoggableInterface
{
    function setLogger( \CLIFramework\Logger $logger );

    // we use __call magic to call logger
    // function info($msg,$level = 0);
    // function info2($msg,$level = 0);
}

