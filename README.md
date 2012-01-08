Onion
=====

Onion, The fast approach to make/install/bundle PEAR packages for PHP.

Onion is able to generate a PEAR2-compatible package.xml file from a very simple config
file, you can release your PEAR package very quickly!

And through Onion, you can also install PEAR dependencies into local bundle (just like Ruby Bundler).

Onion is not target to replace other package manager, its target is to make current PEAR ecosystem easier.

Let's keep hard long PEAR package.xml away! :-)

## Onion is for people want to..

* Build PEAR package easily, quickly.
* Install PEAR dependencies into local project directory. (like Ruby Bundler)

## Requirement

* PHP 5.3
* simplexml extension
* DOMDocument extension
* curl

## The minimal spec file

```ini
[package]
name = Onion
desc = package description
version = 0.0.1
author = Yo-An Lin <cornelius.howl@gmail.com>
channel = pear.php.net
```

## A Quick tutorial for building PEAR package

Get onion.phar file:

    $ curl https://github.com/c9s/Onion/raw/master/onion.phar > ~/bin/onion.phar

Please make sure your directory structure:

    src/   # contains php source code
    doc/   # documentation files (optional)
    tests/ # unit testing files

Fill your `package.ini` file:

    [package]
    name = Onion
    desc = package description
    version = 0.0.1
    channel = pear.php.net
    author = Yo-An Lin <cornelius.howl@gmail.com>

To generate a package.xml 2.0 spec file for PEAR and build a PEAR package, just run:

    $ php ~/bin/onion.phar build

Then Your PEAR package is out!


## Adding package dependencies

    [package]
    name = Onion
    desc = package description
    version = 0.0.1
    author = Yo-An Lin <cornelius.howl@gmail.com>

    [require]
    php = 5.3
    pearinstaller = 1.4
    pear.php.net/PackageName = 0.0.1


## Bundle

Install PEAR dependencies into vender/ directory

    $ onion.phar -d bundle


Note: Current bundle command does not support PEAR special features like:

- PEAR Task: like replace content, rename ... etc
- PECL installation

## Compile package to Phar executable/library file

An example, we use onion.phar to compile our executable file `onion.phar`:

    $ onion.phar compile \
        --executable \
        --classloader \
        --bootstrap scripts/onion.embed \
        --lib src \
        --lib ../CLIFramework/src \
        --lib ../GetOptionKit/src \
        --output onion.phar

## Available Config Tags

please checkout [SPEC](SPEC.md)

A more detailed example
========================

    [package]
    name        = your package name
    desc        = package description
    summary     = ....                  # optional, default to Description
    homepage    = http://your.web.com   # optional
    license     = PHP                   # optional, default to PHP
    version     = 0.0.1
    version-api = 0.0.1                 # optional, defualt to "version"
    channel     = pear.php.net          # default

    ; lead developer
    author         = Yo-An Lin <cornelius.howl@gmail.com>

    ; other authors
    authors[]      = Yo-An Lin <cornelius.howl@gmail.com>
    authors[]      = Yo-An Lin

	 ; contributors ...
    contributors[] = ...                # optional
    maintainers[]  = ...                # optional

    [required]
    php = > 5.3
    pearinstaller = '1.4.1'

    ; pear package based on channel
    pear.channel.net/package = 1.1

    ; pear package based on URI
    package = http://www.example.com/Foo-1.3.0
    package = conflicts

    extension/reflection = 
    extension/ctype = 
    extension/pcre = 

    [roles]

    ; mapping files to role
    your_script = bin

    ; glob is supported.
    *.md = doc
    *.php = php

    [optional remoteshell]
    hint = Add support for Remote Shell Operations
    channel/test = 0.1
    channel/foo = 0.2
    extensions[] = ssh2

## What People Say

nrk: 

    its own package.ini file looks simple enough to edit and maintain.

    It's been super-easy to get up and running and I haven't encountered any real
    problems.  Onion looks good already.  

2011-12-18 <https://github.com/nrk/predis/commit/104cd1eae7f3fb2bff3ccd3193c3e31b8502af56>


## Community

If you have questions about Onion or want to help out, come and join us in the #onion-dev channel on `irc.freenode.net`.
