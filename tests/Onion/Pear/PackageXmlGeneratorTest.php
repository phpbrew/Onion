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
		$logger = \Onion\Application::getInstance()->getLogger();
		ok( $logger );
		$logger->setLevel( 0 ); // quite 

        $config = new \Onion\PackageConfigReader();
        $config->setLogger( $logger );
		ok( $config );

        $package = $config->read( 'package.ini' );
		ok( $package );

		$generator = new \Onion\Pear\PackageXmlGenerator( $package );
		$generator->setLogger( $logger );

		$xml = $generator->generate();
		ok( $xml , 'xml build ok' );

        // do validations from xsd file.
	}

    function test2()
    {
        $logger = \Onion\Application::getInstance()->getLogger();
        $logger->setLevel( 0 );

        $config = new \Onion\PackageConfigReader();
        $config->setLogger( $logger );

        $package = $config->read( 'package.ini' );
        $package->config->array['roles']['src'] = 'php';

        $generator = new \Onion\Pear\PackageXmlGenerator( $package );
        $generator->setLogger( $logger );

        $installElement = '<install name="src/Onion/Application.php" as="Onion/Application.php"/>';
        $generatedXml = $generator->generate();

        is(1, substr_count($generatedXml, $installElement), 'Only one install should appear per file.');
    }
}
