<?php

class PackageTest extends PHPUnit_Framework_TestCase
{


	function testPackage()
	{
		$package = new Onion\Package\Package;
		$package->name = 'test';
		$package->version = '1.0.1';
		$package->desc = 'description';
		$package->summary = 'summary';

		ok( $package );

		$package->addDependency('core','php','>= 1.2.1');
		$package->addDependency('pear','PEARX','>= 0',array( 
			'type' => 'pear',
			'channel' => 'pear.corneltek.com',
	   	));

		$package->addDependency('pear','SerializerKit','> 0',array( 
			'type' => 'pear',
			'channel' => 'pear.corneltek.com',
		));

		ok( $package->getDependencies() );

		foreach( $package->getDependencies() as $dep ) {
			ok( $dep );
		}
	}
}

