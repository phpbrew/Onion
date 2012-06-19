<?php

class InstallerTest extends PHPUnit_Framework_TestCase
{
    function test()
    {
        $installer = new Onion\Installer( array( 
            'lib_dir' => 'tests/tmp/vendor'
        ));
        ok($installer);
    }
}


