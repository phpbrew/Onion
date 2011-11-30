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
namespace tests\Onion;
use Onion\SpecUtils;
class SpecUtilsTest extends \PHPUnit_Framework_TestCase 
{
	function testVersion() 
	{
		$v = SpecUtils::parseVersion('0.0.1');
		ok( $v );

		$v = SpecUtils::parseVersion(' < 0.0.1 ');
		ok( $v );

		$v = SpecUtils::parseVersion(' 0.0.3 <=> 0.0.6 ');
		ok( $v );
		is( '0.0.3', $v['min'] );
		is( '0.0.6', $v['max'] );
	}

	function testAuthor()
	{
		ok( SpecUtils::parseAuthor('Yo-An Lin "c9s" <cornelius.howl@gmail.com>') );
	}
}
