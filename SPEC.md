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

    (optional) defaults to the first line of description.
    
    summary about this package, if this field is skipped, the
    summary will be extracted from description.

* `homepage`

    Website page url about this package.

* `license`

    License type of this package. valids are: `PHP`, `MIT`, `BSD`, `GPL`, `LGPL`.

* `version`

    Package version.

* `version.api`

    (optional)

    The API version.


* `channel`

    (optional) defaults to pear.php.net
    
    Package channel. The channel which package belongs to.

* `author`

    Package author, the format as below:

        author = Yo-An Lin <cornelius.howl@gmail.com>

    To define multiple authors:

        authors[] = Another Author <foo@foo.com>
        authors[] = Another Author II <bar@foo.com>

# Dependency section

The dependency is defined in `[requires]` section. 

## The format of php dependency

    php = {version expression}

For example:

    php = 5.3

## The format of pearinstaller dependency

    pearinstaller = {version expression}

For example:

    pearinstaller = 1.4.1

## The format of package dependency

    {channel name}/{packagename} = {version expression}
    {channel domain}/{packagename} = {version expression}

## The format of package dependency based on URI

    {packagename} = {URI}

For example:

    Foo = http://www.example.com/Foo-1.3.0

## The format of extension dependency

    extensions[] = {extension name}

For example:

    extensions[] = reflection
    extensions[] = ctype
    extensions[] = pcre

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
