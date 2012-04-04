<?php
require 'tests/helpers.php';
require 'vendor/pear/Universal/ClassLoader/SplClassLoader.php';
$classLoader = new \Universal\ClassLoader\SplClassLoader(array( 
    'Onion' => 'src',
    'CurlKit' => 'src',
    'PEARX' => 'vendor/pear',
    'CacheKit'     => 'vendor/pear',
    'CLIFramework' => 'vendor/pear',
    'GetOptionKit' => 'vendor/pear',
    'TestApp' => 'tests',
    // 'Pyrus' => '/Users/c9s/git/others/php/pyrus/Pyrus/src',
));
$classLoader->useIncludePath(false);
$classLoader->register();
