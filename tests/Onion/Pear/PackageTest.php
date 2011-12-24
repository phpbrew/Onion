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
namespace tests\Onion\Pear;

class PackageTest extends \PHPUnit_Framework_TestCase 
{
    function test() 
    {
        $p = new \Onion\Pear\Package;
        $p->name = 'name';
        $p->desc = 'desc';
        $p->channel = 'channel';
        $p->releases = array(
            '0.0.1' => 'stable',
        );
        $p->deps = array(
            '0.0.1' => array( 'required' => array( 'php' => array( 'min' => '5.0' ) ) )
        );

        $str = serialize( $p );
        ok( $str );

        $pNew = unserialize( $str );
        ok( $pNew );
        ok( $pNew->name );
        ok( $pNew->desc );
        ok( $pNew->channel );
        ok( $pNew->releases['0.0.1'] );
        ok( $pNew->deps['0.0.1'] );

        $ary = $pNew->toArray();
        ok( $ary['name'] );
        ok( $ary['desc'] );
        ok( $ary['deps'] );
    }
}

