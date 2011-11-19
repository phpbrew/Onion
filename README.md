GenPEAR
=======
Use simple package.ini file to generate hard PEAR package.xml!

Example spec file:

    [package]
    name = GenPEAR
    desc = package description
    version = 0.0.1

    [require]
    php = >=5.3
    symfony/process = >=1.1

    [author]
    name = Yo-An Lin
    email = cornelius.howl@gmail.com


If you're new to `genpear`, you have to edit your ~/.pear.ini file:

    [author]
    name = Your name
    email = email@host.com

To generate a simple package.ini file, genpear will generate one for you:

    $ genpear init

Then you got package.ini file.

To generate a package.xml 2.0 spec file for PEAR, just run:

    $ genpear to-pear

Then you should be able to run pear command to build the package:

    $ pear package

COMPOSER SUPPORT
================
To support composer/composer , just run:

    $ genpear to-composer

A MORE DETAILED EXAMPLE
========================

    [package]
    name      = GenPEAR
    desc      = package description
    summary   = ....                  # optional, default to Description
    homepage  = http://your.web.com  # optional
    license   = MIT                   # optional, default to PHP
    verbose   = 0.0.1
    authors[] = Yo-An Lin <cornelius.howl@gmail.com>

    data = path/to/pkg_data
    src  = path/to/src
    test = path/to/test
    web  = path/to/web

    [require]
    php = >=5.3
    symfony/process = >=1.1

REFERENCE
=========
http://pear.php.net/manual/en/guide.users.dependencytracking.generatingpackagexml.php
