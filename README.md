Onion
=======
Onion, The fast approach to make/install packages for PHP.

Onion is able to generate a PEAR2-compatible package.xml file from a very simple config
file, you can release your PEAR package very quickly!

Let's keep hard long PEAR package.xml away! :-)


(we are still in development, patches, feature requests are welcome!)


The minimal spec file
---------------------

    [package]
    name = Onion
    desc = package description
    version = 0.0.1
    author = Yo-An Lin <cornelius.howl@gmail.com>

    [require]
    php = >=5.3



Quick Tutorial
--------------
Get onion.phar file:

    $ curl https://github.com/c9s/Onion/raw/master/onion.phar > ~/bin/onion.phar

If you're new to `Onion`, you might need to edit your author information in ~/.onion.ini file,
this helps you generate a new package.ini file, your ~/.onion.ini file might be like:

    [author]
    name = Your name
    email = email@host.com

Fill your package.ini file:

    [package]
    name = Onion
    desc = package description
    version = 0.0.1
    author = Yo-An Lin <cornelius.howl@gmail.com>

    [require]
    php = >=5.3

To generate a package.xml 2.0 spec file for PEAR and build a PEAR package, just run:

    $ php ~/bin/onion.phar build

Then Your PEAR package is out!




Requirement
-----------

* PHP 5.3
* simplexml extension

Available Config Tags
---------------------

[package] section:

* `name`

* `desc`

* `summary` (optional)

* `version`

* `api_version` (optional)

* `author`

* `authors`

* `channel`

[requires] section:

* `php`

* `pearinstaller`

* `extensions[]`

A more detailed example
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


Reference
---------
INI format spec
http://www.cloanto.com/specs/ini/

Package structure
http://pear.php.net/manual/en/pyrus.commands.make.php

package.xml 2.0 tags
http://pear.php.net/manual/en/guide.developers.package2.tags.php

PEAR2 Coding Standard
http://pear.php.net/manual/en/pear2cs.php

Pyrus
http://pear.php.net/manual/en/pyrus.php


http://pear.php.net/manual/en/guide.users.concepts.php

http://pear.php.net/manual/en/pyrus.extending.packagefile.php

http://pear.php.net/manual/en/guide.users.dependencytracking.generatingpackagexml.php



Deployment PEAR
http://www.eschrade.com/page/deployment-pear-4c228790/

Packagist
http://packagist.org/about

PSR-0 
https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
