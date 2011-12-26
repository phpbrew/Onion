HACKING
=======

Development environment requirement:

* curl extension
* simplexml extension
* DOMdocument extension
* PHPUnit

clone it, or fork it:

    git clone https://github.com/c9s/Onion.git

Run ./onion.phar to install dependencies:

    ./onion.phar -d bundle

Pear Dependencies will be installed into vendor/pear/

Run `scripts/onion` can test onion application without re-compile it.

## Unit Testing

Run `phpunit`.

## Compile

Run `scripts/compile.sh`

## Release

Run `scripts/release.sh`
