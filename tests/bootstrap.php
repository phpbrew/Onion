<?php
require 'tests/helpers.php';
require 'vendor/pear/Universal/ClassLoader/SplClassLoader.php';
define('BASEDIR',dirname(dirname(__FILE__)));
$classLoader = new \Universal\ClassLoader\SplClassLoader(array( 
    'Onion' => BASEDIR . '/src',
    'CurlKit' => BASEDIR . '/src',
    'PEARX' => BASEDIR . '/vendor/pear',
    'CacheKit'     => BASEDIR . '/vendor/pear',
    'CLIFramework' => BASEDIR . '/vendor/pear',
    'GetOptionKit' => BASEDIR . '/vendor/pear',
    'TestApp' => 'tests',
));
$classLoader->useIncludePath(false);
$classLoader->register();
