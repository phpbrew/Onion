#!/bin/bash
rm -rf .onion/
rm -rf vendor/

# bundle with new dependencies
php onion bundle || exit

# compile to phar file
scripts/compile.sh || exit

# build new package.xml
php onion -d build || exit

# use pear to install 
sudo pear install -a -f package.xml || exit

# git commit -a -m 'Make new release'
# git push origin HEAD
