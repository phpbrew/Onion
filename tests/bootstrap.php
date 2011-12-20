<?php
require 'tests/helpers.php';
require 'Universal/ClassLoader/SplClassLoader.php';
$classLoader = new \Universal\ClassLoader\SplClassLoader(array( 
    'Onion' => 'src',
    'CLIFramework' => 'src',
    'TestApp' => 'tests',
    'Pyrus' => '/Users/c9s/git/others/php/pyrus/Pyrus/src',
));
$classLoader->useIncludePath(true);
$classLoader->register();
