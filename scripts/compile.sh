#!/bin/bash
# ./onion.phar compile --lib src --lib ../CLIFramework/src --lib ../GetOptionKit/src --classloader --bootstrap scripts/onion.embed --executable --compress=bz2 --output onion.phar
./scripts/onion compile --lib src --lib ../CLIFramework/src --lib ../GetOptionKit/src --classloader --bootstrap scripts/onion.embed --executable --output onion.phar
