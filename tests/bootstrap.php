<?php
require 'SplClassLoader.php';
$classLoader = new SplClassLoader('Onion','src');
$classLoader->register();
$classLoader = new SplClassLoader('CLIFramework','src');
$classLoader->register();
$classLoader = new SplClassLoader();
$classLoader->register();
