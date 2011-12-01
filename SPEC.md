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

* `version-api`

    (optional) inherited from `version`.

    The API version.

* `channel`

    (optional) defaults to pear.php.net
    
    Package channel. The channel which package belongs to.

* `extends`

	Superseding a package

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

* `stability-release`

	(optional) Release stability.

* `stability-api`

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


## The format of PHP dependency

    php = {version expression}

For example:

    php = 5.3

Optional.
Default: 5.3

## The format of pearinstaller dependency

    pearinstaller = {version expression}

For example:

    pearinstaller = 1.4.1

Optional:
Default: 1.4

## The format of package dependency

    {channel name}/{packagename} = {version expression}
    {channel domain}/{packagename} = {version expression}

## The format of package dependency based on URI

    {packagename} = {URI}

For example:

    Foo = http://www.example.com/Foo-1.3.0

## The format of extension dependency

    ext/{extension name} = {version expression}

For example:

    ext/reflection = 0.0.1
    ext/ctype = 
    ext/pcre = 

# Optional dependency section

the spec is inherited from `[requires]` section.

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
	{path} = {role name}

Available roles:

- php
- doc
- test
- script
- data

Default directory mapping to roles:

- php => `src` dir
- doc => `docs` dir
- test => `tests` dir
- script => `bin` dir
- data => `data`, `examples` dir

	