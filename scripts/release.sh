#!/bin/bash

# bundle with new dependencies
./onion -d bundle || exit

# compile to phar file
scripts/compile.sh || exit

# build new package.xml
./onion -d build --pear || exit

# use pear to install 
sudo pear install -a -f package.xml || exit

# git commit -a -m 'Make new release'
# git push origin HEAD
