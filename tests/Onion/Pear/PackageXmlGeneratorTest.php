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

class PackageXmlGeneratorTest extends \PHPUnit_Framework_TestCase 
{
	function test() 
	{
		$logger = \Onion\Application::getLogger();
		ok( $logger );

        $config = new \Onion\PackageConfigReader();
        $config->setLogger( $logger );
		ok( $config );

        $package = $config->read( 'package.ini' );
		ok( $package );

		$generator = new \Onion\Pear\PackageXmlGenerator( $package );
		$generator->setLogger( $logger );

		$xml = $generator->generate();
		ok( $xml , 'xml build ok' );

		var_dump( $xml ); 
	}
}
