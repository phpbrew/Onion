HACKING
=======

Development environment requirement:

* curl extension
* simplexml extension
* DOMdocument extension
* PHPUnit

clone it, or fork it:

    git clone https://github.com/c9s/Onion.git

Run ./onion.phar to install dependencies:

    ./onion.phar -d bundle

Pear Dependencies will be installed into vendor/pear/

Run `scripts/onion` can test onion application without re-compile it.


## Structures

CLI Application bootstrap is located at `scripts/onion`.

Commands are registered in `src/Onion/Application.php`, this is where CLI application start.

Commands are putted in src/Onion/Command/

About GetOpt stuff, please see GetOptionKit.

## Unit Testing

Run `phpunit`.

## Compile

Run `scripts/compile.sh`

## Release

Run `scripts/release.sh`


## Reference

INI format spec: <http://www.cloanto.com/specs/ini/>

Package structure
<http://pear.php.net/manual/en/pyrus.commands.make.php>


package.xml 2.0 tags
<http://pear.php.net/manual/en/guide.developers.package2.tags.php>


package.xml dependency
<http://pear.php.net/manual/en/guide.developers.package2.dependencies.php>


PEAR2 Coding Standard
<http://pear.php.net/manual/en/pear2cs.php>

Pyrus
<http://pear.php.net/manual/en/pyrus.php>

PEAR
<http://pear.php.net/manual/en/guide.users.concepts.php>
<http://pear.php.net/manual/en/pyrus.extending.packagefile.php>
<http://pear.php.net/manual/en/guide.users.dependencytracking.generatingpackagexml.php>

PEAR Installer
<http://pear.php.net/manual/en/developers-core.php>
<http://pear.php.net/manual/en/developers-changes14.php>

Deployment PEAR
<http://www.eschrade.com/page/deployment-pear-4c228790/>

PSR-0 
<https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md>
