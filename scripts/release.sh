#!/bin/bash

# bundle with new dependencies
./onion -d bundle

# compile to phar file
scripts/compile.sh

# build new package.xml
./onion -d build --pear

# use pear to install 
sudo pear install -a -f package.xml

git commit -a -m 'Make new release'

git push origin HEAD
