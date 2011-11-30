<?php
require 'UniversalClassLoader/SplClassLoader.php';
$classLoader = new \UniversalClassLoader\SplClassLoader(array( 
    'Onion' => 'src',
    'CLIFramework' => 'src',
));
$classLoader->useIncludePath(true);
$classLoader->register();
