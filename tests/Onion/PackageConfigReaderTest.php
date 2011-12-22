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
use PHPUnit_Framework_TestCase;
class PackageConfigReaderTest extends PHPUnit_Framework_TestCase 
{
    function test() 
    {
        $logger = new \CLIFramework\Logger;
        ok( $logger );

        $reader = new PackageConfigReader;
        $reader->setLogger( $logger );

        ob_start();
        $reader->info( 'msg' );
        $reader->error( 'msg' );
        $reader->info2( 'msg' );
        ob_end_clean();

        ok( $reader );

        ob_start();
        $pkg = $reader->read( 'tests/data/package.ini' );
        ob_end_clean();

        ok( $pkg );
            

    }
}

