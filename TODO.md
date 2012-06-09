TODO
====
* system-wide install support
* install dir option support

* support github installation
* bundle dependencies into ./local/ and ./onion.lock (json)

* compile command: which compile source into phar file.
* dist:   make php package. 
* support github dependency.
* separate CLIFramework to repository.

Compile Command
---------------
* support setSignatureAlgorithm through openssl.pem file
    $phar->setSignatureAlgorithm(Phar::OPENSSL, file_get_contents('private_key_here.pem'));
* replace `@php_dir@`

PEAR Channel Discover
----------------------
channel.xml

    <?xml version="1.0" encoding="UTF-8" ?>
    <channel version="1.0" xmlns="http://pear.php.net/channel-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/channel-1.0 http://pear.php.net/dtd/channel-1.0.xsd">
        <name>pear.corneltek.com</name>
        <summary>Corneltek PEAR channel</summary>
        <suggestedalias>corneltek</suggestedalias>
        <servers>
            <primary>
                <rest>
                    <baseurl type="REST1.0">http://pear.corneltek.com/rest/</baseurl>
                    <baseurl type="REST1.1">http://pear.corneltek.com/rest/</baseurl>
                    <baseurl type="REST1.2">http://pear.corneltek.com/rest/</baseurl>
                    <baseurl type="REST1.3">http://pear.corneltek.com/rest/</baseurl>
                </rest>
            </primary>
        </servers>
    </channel>

rest/c/

rest/p/packages.xml

    <a xmlns="http://pear.php.net/dtd/rest.allpackages" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.allpackages http://pear.php.net/dtd/rest.allpackages.xsd">
        <c>pear.corneltek.com</c>
        <p>CLIFramework</p>
        <p>GetOptionKit</p>
        <p>GrindKit</p>
        <p>Onion</p>
        <p>PHPUnit_TestMore</p>
        <p>Universal</p>
    </a>


rest/r/{packagename}/allrelease2.xml
                deps.0.0.4.txt (unserialize)
                latest.txt
                v2.0.0.4.xml
                beta.txt (version number)
                stable.txt (version number)


get/CLIFramework-0.0.1.tar
get/GetOptionKit-0.0.6.tgz

Package Install
---------------
* Parse package.ini to Package object
  * build dependency arguments
    pure array
    contains type: pear, library, extension or other dependency
* pass package object to dependency resolver 
    dependency resolver get the dependency informations
    expand the dependency to package object
        for pear package:
            discover channel info and save it
            retrieve the name, version, dependency from channel
                check if the dependency already exists, if not add it to dependency pool
                expand the dependency to package object...
    expand done.
    use installer to install these packages from dependency pool
        for pear package
            check if current package is installed

            if it's installed
                check if upgrade is needed if dep is not locked.

            if it's not installed
                run pear package installer
                    - fetch pear package from channel (get channel from channel pool).
                    - extract pear pacakge.
                    - parse package.xml.
                    - A Simple PackageXml Parser.
                        - PackageXml Dependency Parser.
                        - PackageXml Content Installer.
                            - install-as 
                            - dir traversal
                        - Parse PackageXml to Package.
                    - install content files into base library.
                    - return pear lib path 
                        and installed version to installer.
        for library package
            run LibraryPackage installer
                check if the same library installed (by name or resource)
                fetch library from resource (url,git,svn or hg)
                    to target baseurl
                    return library path 
                        and installed revision 
                        or version to installer

* package xml writer.

* Resolve Package dependency
  * PearChannel Resource Handler => convert to Package object.
  * Other Resource Handler (like git or svn)
* Dependency manager 
  * Dependency Pool
* Installer

* Dependency Lock file generator.
* Auto Classloader generator (PSR-0)

* Cache channel and packages info
* Dependency Lock file reader
