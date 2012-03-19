#!/bin/bash
php ./scripts/onion -d compile \
    --lib src \
    --lib vendor/pear \
    --classloader \
    --bootstrap scripts/onion.embed \
    --executable \
    --output onion.phar
mv onion.phar onion
chmod +x onion
