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

The require dependency is defined in `[require]` section.

`[require]` is optional.

The optional dependency is defined in `[optional]` section.

`[optional]` is optional.

For example,

    [optional]
    channel/pkg = 0.0.1

## Dependency Version Expression

To specify minimal required version:

    pkg = 0.001

To specify max required version:

    pkg = "< 0.1.0"

Specify minimal and max required version:

    pkg = "0.001 <=> 0.1.0"

## Format of PHP dependency

    php = "{version expression}"

For example:

    php = "5.3"

Optional.

Default: 5.3

## Format of pearinstaller dependency

    pearinstaller = "{version expression}"

For example:

    pearinstaller = "1.4.1"

Optional:
Default: 1.4

## Format of PEAR package dependency

Specify channel host and your package name

    {channel host}/{packagename} = {version expression}

## Format of PEAR package dependency from URI

    {packagename} = {URI}

For example:

    Foo = http://www.example.com/Foo-1.3.0

## Format of extension dependency

    ext/{extension name} = "{version expression}"

Or

    extension/{extension name} = "{version expression}"

For example:

    ext/reflection = 0.0.1
    ext/ctype = 
    ext/pcre = 

# Special Dependency Section

For PEAR type packages, we don't need to specify autoload path.

For library type packages, we need to specify autoload path for autoloading packages.

## Format of SVN resource dependency

    [require]
    pkgname = resource   ; define the package requirement information later in [resource] section.

    [resource pkgname]
    type = pear
    svn = http://host.com/to/svn/trunk
    revision = HEAD

## Format of Git resource dependency

For PEAR type package resource:

    [require]
    pkgname = resource   ; define the package requirement information later in [resource] section.

    [resource pkgname]
    type = pear
    git  = git://github.com/c9s/GetOptionKit.git
    branch = master

For Library type package resource:

    [resource pkgname]
    type = library
    git = git://github.com/c9s/GetOptionKit.git
    autoload = lib

## Format of GitHub resource dependency

    [resource pkgname]
    type = library
    github = foo/bar
    protocol = http
    branch = master
    autoload = src

## Format of URL resource dependency

    [resource pkgname]
    type = library
    url  = http://.........
    autoload = src


# Optional PEAR package dependency section

The spec is inherited from `[require]` section.

## Optional Group of PEAR Package Dependency

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

    [optionalgroup "SSH"]
    hint = Add support for Remote Shell Operations
    pear.php.net/SSH_RemoteShell = 
    extensions[] = ssh2
    special = resource   ; define the package requirement information later in [resource] section.

# Optional Special package dependency section

    [resource special]
    type = library

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
