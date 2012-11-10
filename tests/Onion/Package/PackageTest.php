<?php

namespace tests\Onion\Package;

class PackageTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldUseIniFileConfiguredDirectoryStructure()
    {
        $config = new \Onion\PackageConfigReader();

        $package = $config->read(__DIR__ . '/fixtures/stub.ini');

        $fileStructure = $package->getDefaultStructureConfig();

        $this->assertEquals(array('lib'), $fileStructure['php']);
    }

    public function testShouldUseDefaultDirectoryStructure()
    {
        $config = new \Onion\PackageConfigReader();

        $package = $config->read(__DIR__ . '/fixtures/stub_with_no_structure.ini');

        $fileStructure = $package->getDefaultStructureConfig();

        $this->assertEquals(array('src'), $fileStructure['php']);
    }
}
