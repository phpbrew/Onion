Installer Flow
===============

1. read the package.ini from current directory.
    
    see Onion\Command\InstallCommand

    and use PackageConfigReader to read config file:

        $reader = new \Onion\PackageConfigReader;


2. create a dependency resolver object

    pass the package meta information to dependency resolver
    and resolve all package dependencies.

    the package dependencies are stored in a dependency pool of dependency resolver.

3. create an installer, iterating all dependencies and use specific installer for each package.

    For PEAR packages, use PEARInstaller
    For Library files, use LibraryInstaller (not support yet)

