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

    $ onion build

Then you should be able to run pear command to build the package:

    $ pear package

COMPOSER SUPPORT
================
To support composer/composer , just run:

    $ onion build --composer

IGNORE FILES
============

To ignore some files to be packed, you can also set the ignore_file attribute
in your package.ini file:

    ignore_file = ignore_list.txt

Onion also supports .gitignore, if you don't specify ignore_file attribute,
Onion will use .gitignore file to ignore files.

A MORE DETAILED EXAMPLE
========================

    [package]
    name        = your package name
    desc        = package description
    summary     = ....                  # optional, default to Description
    homepage    = http://your.web.com   # optional
    license     = MIT                   # optional, default to PHP
    version     = 0.0.3
    api_version = 0.0.1                 # optional, defualt to "version"
    authors[]      = Yo-An Lin <cornelius.howl@gmail.com>
    contributors[] = ...                # optional
    maintainers[]  = ...                # optional
    channel     = pear.php.net
    repository  = github.com/c9s....

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

    ; details for LICENSE (optional)
    [license]
    file = ....
    uri  = ....


REFERENCE
=========
Package structure
http://pear.php.net/manual/en/pyrus.commands.make.php

package.xml 2.0 tags
http://pear.php.net/manual/en/guide.developers.package2.tags.php

ini format spec
http://www.cloanto.com/specs/ini/

http://pear.php.net/manual/en/guide.users.concepts.php
http://pear.php.net/manual/en/pyrus.extending.packagefile.php
http://pear.php.net/manual/en/guide.users.dependencytracking.generatingpackagexml.php

Pyrus
http://pear.php.net/manual/en/pyrus.about.php

