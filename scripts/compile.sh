#!/bin/bash
./scripts/onion -d compile \
    --lib src \
    --lib ../CLIFramework/src \
    --lib ../GetOptionKit/src \
    --classloader \
    --bootstrap scripts/onion.embed \
    --executable \
    --output onion.phar
