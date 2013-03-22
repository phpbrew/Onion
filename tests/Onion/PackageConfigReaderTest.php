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
        $reader = new PackageConfigReader;
        ok( $reader );

        ob_start();
        $pkg = $reader->read( 'tests/data/package.ini' );
        ob_end_clean();

        ok( $pkg );
        ok( $pkg->name );
        ok( $pkg->version );
        ok( $pkg->summary );
        ok( $pkg->desc );
        ok( $pkg->stability );
        $resources = $reader->getResources();
    }
}

