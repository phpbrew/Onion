#!/bin/bash

# bundle with new dependencies
./onion.phar -d bundle

# compile to phar file
scripts/compile.sh

chmod +x onion.phar

# build new package.xml
./onion.phar -d build

# use pear to install 
sudo pear install -a -f package.xml
