Onion
=======
Use simple package.ini file to generate hard PEAR package.xml!

Example spec file:

    [package]
    name = Onion
    desc = package description
    version = 0.0.1

    [require]
    php = >=5.3
    symfony/process = >=1.1

    [author]
    name = Yo-An Lin
    email = cornelius.howl@gmail.com

If you're new to `onion`, you have to edit your ~/.pear.ini file:

    [author]
    name = Your name
    email = email@host.com

To generate a simple package.ini file, onion will generate one for you:

    $ onion init

Then you got package.ini file.

To generate a package.xml 2.0 spec file for PEAR, just run:

    $ onion to-pear

Then you should be able to run pear command to build the package:

    $ pear package

COMPOSER SUPPORT
================
To support composer/composer , just run:

    $ onion to-composer

A MORE DETAILED EXAMPLE
========================

    [package]
    name      = GenPEAR
    desc      = package description
    summary   = ....                  # optional, default to Description
    homepage  = http://your.web.com   # optional
    license   = MIT                   # optional, default to PHP
    version     = 0.0.3
    api_version = 0.0.1               # optional
    authors[] = Yo-An Lin <cornelius.howl@gmail.com>
    channel   = pear.php.net

    [structure]
    data = path/to/pkg_data
    src  = path/to/src
    test = path/to/test
    web  = path/to/web
    bin  = path/to/bin
    conf = path/to/conf
    resources = path/to/resources

    [requires]
    php = '>=5.3'
    pearinstaller = '1.4.1'
    symfony/process = '>=1.1'
    exts[] = 'reflection'
    exts[] = 'ctype'
    exts[] = 'pcre'

    [recommends]

REFERENCE
=========
http://pear.php.net/manual/en/guide.users.dependencytracking.generatingpackagexml.php

Pyrus
http://pear.php.net/manual/en/pyrus.about.php
