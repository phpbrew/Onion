Onion
=====

Onion, The fast approach to make/install/bundle PEAR packages for PHP.

Onion is able to generate a PEAR2-compatible package.xml file from a very simple config
file, you can release your PEAR package very quickly!

And through Onion, you can also install PEAR dependencies into local bundle (just like Ruby Bundler).

Onion is not target to replace other package manager, its target is to make current PEAR ecosystem easier.

Let's keep hard long PEAR package.xml away! :-)

[![Build Status](https://secure.travis-ci.org/c9s/Onion.png)](http://travis-ci.org/c9s/Onion)

## Onion is for people want to..

* Build PEAR package easily, quickly.
* Install PEAR dependencies into local project directory. (like Ruby Bundler)

## What People Say

nrk: 

    its own package.ini file looks simple enough to edit and maintain.

    It's been super-easy to get up and running and I haven't encountered any real
    problems.  Onion looks good already.  

2011-12-18 <https://github.com/nrk/predis/commit/104cd1eae7f3fb2bff3ccd3193c3e31b8502af56>

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

Get and install onion:

    $ curl -s http://install.onionphp.org/ | sh

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

    $ onion build --pear

Then Your PEAR package is out!

The `--pear` flag is optional.

See:

    $ onion help build

## Adding package dependencies

    [package]
    name = Onion
    desc = package description
    version = 0.0.1
    author = Yo-An Lin <cornelius.howl@gmail.com>
    channel = pear.php.net

    [require]
    php = 5.3
    pearinstaller = 1.4
    pear.php.net/PackageName = 0.0.1

## Bundle

Install PEAR dependencies into vendor/ directory

    $ onion -d bundle

### PEAR Features not support yet

Current bundle command does not support PEAR special features like:

- PEAR Task: like replace content, rename ... etc
- PECL installation

## Compile package to Phar executable/library file

An example, we use onion to compile our executable file `onion.phar`:

    $ onion compile \
        --executable \
        --classloader \
        --bootstrap scripts/onion.embed \
        --lib src \
        --lib ../CLIFramework/src \
        --lib ../GetOptionKit/src \
        --output onion.phar

## Available Config Tags

please checkout [doc/SPEC.md](https://github.com/c9s/Onion/blob/master/doc/SPEC-v2.md)


## Customize roles

There are many built-in roles so that you don't need to define it by yourself, built-in roles are:

- src/   php role
- docs/  doc role
- tests/ test role
- \*.md  doc role

But you can add custom roles by yourself.

```ini
[roles]
path/to/data = data
path/to/library = php
path/to/doc = doc
```


A more detailed example
========================

    [package]
    name        = your package name
    desc        = package description
    summary     = ....                  # optional, default to Description
    homepage    = http://your.web.com   # optional
    license     = PHP                   # optional, default to PHP
    version     = 0.0.1
    version.api = 0.0.1                 # optional, default to "version"
    channel     = pear.php.net          # default

    ; lead developer
    author         = Yo-An Lin <cornelius.howl@gmail.com>

    ; other authors
    authors[]      = Yo-An Lin <cornelius.howl@gmail.com>
    authors[]      = Yo-An Lin

	 ; contributors ...
    contributors[] = ...                # optional
    maintainers[]  = ...                # optional

    [require]
    php = '> 5.3'
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

## Hacking

Make a fork from Onion and clone it:

    $ git clone git@github.com:c9s/Onion.git
    $ cd Onion

Run onion to download bundles

    $ php onion.phar -d bundle

To run unit tests:

    $ phpunit

To test onion command:

    $ scripts/onion help

To compile onion:

    $ scripts/compile.sh

## Community

If you have questions about Onion or want to help out, come and join us in the #onion-dev channel on `irc.freenode.net`.
