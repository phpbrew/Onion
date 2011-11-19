GenPEAR
=======
Use simple package.ini file to generate hard PEAR package.xml!

Example spec file:

    [package]
    name = GenPEAR
    desc = package description
    homepage = http://your.web.com
    license = MIT

    [author]
    name = Yo-An Lin
    email = cornelius.howl@gmail.com

    [require]
    php = >=5.3
    symfony/process = >=1.1

If you're new to `genpear`, you have to edit your ~/.pear.ini file:

    [author]
    name = Your name
    email = email@host.com

To generate a simple package.ini file, genpear will generate one for you:

    $ genpear init

Then you got package.ini file.

To generate a package.xml 2.0 spec file for PEAR, just run:

    $ genpear pear init

Then you should be able to run pear command to build the package:

    $ pear build

REFERENCE
=========
http://pear.php.net/manual/en/guide.users.dependencytracking.generatingpackagexml.php
