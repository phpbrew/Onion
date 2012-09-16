<?php

namespace tests\Onion\Package;

class PackageTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldUseIniFileConfiguredDirectoryStructure()
    {
        $logger = new \CLIFramework\Logger();
        $logger->quiet();
        $config = new \Onion\PackageConfigReader();
        $config->setLogger($logger);

        $package = $config->read(__DIR__ . '/fixtures/stub.ini');

        $fileStructure = $package->getDefaultStructureConfig();

        $this->assertEquals(array('lib'), $fileStructure['php']);
    }

    public function testShouldUseDefaultDirectoryStructure()
    {
        $logger = new \CLIFramework\Logger();
        $logger->quiet();
        $config = new \Onion\PackageConfigReader();
        $config->setLogger($logger);

        $package = $config->read(__DIR__ . '/fixtures/stub_with_no_structure.ini');

        $fileStructure = $package->getDefaultStructureConfig();

        $this->assertEquals(array('src'), $fileStructure['php']);
    }
}
