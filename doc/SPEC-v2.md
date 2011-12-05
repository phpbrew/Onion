Package Meta SPEC
=================

# Filename

The package meta filename should be named as `package.ini`.

# Package meta info section

`[package]` section contains meta data for this package, including name,
version, stability etc..

    [package]
    name = Foo
    version = 1.0.0
    desc = Description
    summary = Summary

validate fields are:

* `name`

    Your package name.

* `desc`

    Long description for this package.

* `summary`

    (optional) default to the first line of description.
    
    summary about this package, if this field is skipped, the
    summary will be extracted from description.

* `homepage`

    Website page url about this package.

* `license`

    License type of this package. valids are: `PHP`, `MIT`, `BSD`, `GPL`, `LGPL`.

* `version`

    Package version.

* `version.api`

    (optional) inherited from `version`.

    The API version.

* `channel`

    (optional) defaults to pear.php.net
    
    Package channel. The channel which package belongs to.

* `extends`

	Superseding a package

* `vender`

    (optional). vendor name

* `author`

    Package main author, the format as below:

		  ; Author Name <email> 
        author = Yo-An Lin <cornelius.howl@gmail.com>

    To define multiple authors:

        authors[] = Another Author <foo@foo.com>
        authors[] = Another Author II <bar@foo.com>

	 For multiple authors, username is optional.


* `contributors[]`

	(optional)
	To define contributors:

		contributors[] = Author Name <email@email.com>
		contributors[] = Author 2 <email@email.com>

* `stability`

	(optional) default to 'alpha', when stability is set, `stability-release` and `stability-api` can be ignored.

* `stability.release`

	(optional) Release stability.

* `stability.api`

	(optional) API stability.


# Dependency section

The required dependency is defined in `[required]` section.

`[required]` is optional.

The optional dependency is defined in `[optional]` section.

`[optional]` is optional.

## Dependency Version Expression

To specify minimal required version:

    pkg = 0.001

To specify max required version:

    pkg = < 0.1.0

Specify minimal and max required version:

    pkg = 0.001 <=> 0.1.0


## Format of PHP dependency

    php = {version expression}

For example:

    php = 5.3

Optional.
Default: 5.3

## Format of pearinstaller dependency

    pearinstaller = {version expression}

For example:

    pearinstaller = 1.4.1

Optional:
Default: 1.4

## Format of package dependency

    {channel name}/{packagename} = {version expression}
    {channel domain}/{packagename} = {version expression}

## Format of URI dependency

    {packagename} = {URI}

For example:

    Foo = http://www.example.com/Foo-1.3.0

## Format of extension dependency

    ext/{extension name} = {version expression}

Or

    extension/{extension name} = {version expression}

For example:

    ext/reflection = 0.0.1
    ext/ctype = 
    ext/pcre = 


# Special Dependency Section

## Format of SVN resource dependency

[require pkgname]
svn = http://host.com/to/svn/trunk
revision = HEAD
library = src

XXX: we check package.ini or use package.xml (pear compatible) to check library path.
library path check priority ( ".", "src" )

## Format of Git resource dependency

[require pkgname]
git = git://github.com/c9s/GetOptionKit.git
branch = master
library = src

## Format of GitHub resource dependency

[require symfony]
github = symfony/symfony
protocol = http
branch = master
library = src

# Optional dependency section

the spec is inherited from `[required]` section.

## Optional Group

You can define optional dependencies by groups.

In PEAR2's package.xml we wrote optional groups as below:

    <group name="remoteshell" hint="Add support for Remote Shell Operations">
        <package>
            <name>SSH_RemoteShell</name>
            <channel>pear.php.net</channel>
        </package>
        <extension>
            <name>ssh2</name>
        </extension>
    </group>

You can define your optional dependency group as below:

    [optional "SSH"]
    hint = Add support for Remote Shell Operations
    pear.php.net/SSH_RemoteShell = 
    extensions[] = ssh2

# Role section

Format:

	[roles]
	{path,glob} = {role name}

## Available roles

- php
- doc
- test
- script
- data

## Default directory mapping to roles:

- php    => `src` dir
- doc    => `doc` dir
- test   => `tests` dir
- script => `bin` dir
- data   => `data`, `examples` dir


# Installation

how to install packages into local bundle ?

Targets:

* pear
* github resources
* github reousrces with package.ini or package.xml (PEAR)

* What's the behavior of PEAR
* What's the behavior of Composer
