<?php
require 'tests/helpers.php';
require 'Universal/ClassLoader/SplClassLoader.php';
$classLoader = new \Universal\ClassLoader\SplClassLoader(array( 
    'Onion' => 'src',
    'CLIFramework' => 'src',
    'TestApp' => 'tests',
));
$classLoader->useIncludePath(true);
$classLoader->register();
