Onion
=======
Onion, The fast approach to make/install packages for PHP.

Onion is able to generate a PEAR2-compatible package.xml file from a very simple config
file, you can release your PEAR package very quickly!

Onion is not target to replace other package manager, its target is to make current PEAR ecosystem easier.

Let's keep hard long PEAR package.xml away! :-)

( We are still in development, patches, feature requests are welcome! This
  utility is in alpha development, config spec might change. )


The minimal spec file
---------------------

    [package]
    name = Onion
    desc = package description
    version = 0.0.1
    author = Yo-An Lin <cornelius.howl@gmail.com>

Quick Tutorial
--------------
Get onion.phar file:

    $ curl https://github.com/c9s/Onion/raw/master/onion.phar > ~/bin/onion.phar

If you're new to **Onion**, you might need to edit your author information in `~/.onion.ini` file,
this helps you generate a new package.ini file, your `~/.onion.ini` file might be like:

    [author]
    name  = Your name
    email = email@host.com
    user  = pearId

Fill your `package.ini` file:

    [package]
    name = Onion
    desc = package description
    version = 0.0.1
    author = Yo-An Lin <cornelius.howl@gmail.com>

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
    license     = PHP                   # optional, default to PHP
    version     = 0.0.1
    api_version = 0.0.1                 # optional, defualt to "version"
    author         = Yo-An Lin (c9s) <cornelius.howl@gmail.com>
    authors[]      = Yo-An Lin (c9s) <cornelius.howl@gmail.com>
    authors[]      = Yo-An Lin <cornelius.howl@gmail.com>
    authors[]      = Yo-An Lin
    contributors[] = ...                # optional
    maintainers[]  = ...                # optional
    channel     = pear.php.net          # default


    [requires]
    php = '>=5.3'
    pearinstaller = '1.4.1'

    ; pear package based on channel
    channel/package_name = '>=1.1'

    ; pear package based on URI
    package_name = http://www.example.com/Foo-1.3.0

    extensions[] = 'reflection'
    extensions[] = 'ctype'
    extensions[] = 'pcre'

    [conflicts]
    channel/pkg = 1.1

    [recommends]
    channel/pkg = 1.2

    ; details for LICENSE (optional)
    [license]
    file = ....
    uri  = ....

    [optional remoteshell]
    hint = Add support for Remote Shell Operations
    channel/test = 0.1
    channel/foo = 0.2
    extensions[] = ssh2

    [structure]
    data = path/to/pkg_data
    src  = path/to/src
    test = path/to/tests
    web  = path/to/web
    bin  = path/to/bin
    conf = path/to/conf
    resources = path/to/resources


Community
---------
If you have questions about Onion or want to help out, come and join us in the #onion-dev channel on `irc.freenode.net`.

Reference
---------
INI format spec
http://www.cloanto.com/specs/ini/

Package structure
http://pear.php.net/manual/en/pyrus.commands.make.php


package.xml 2.0 tags
http://pear.php.net/manual/en/guide.developers.package2.tags.php

package.xml dependency
http://pear.php.net/manual/en/guide.developers.package2.dependencies.php

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

