<?php
/*
 * This file is part of the Onion package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Onion\Package;

interface PackageInterface
{

    /**
     * this should return package id
     *
     * for pear, return {pear/channel/package name}
     *
     * for composer, return {composer/vendor/package name}
     *
     * for library, return the {library/library name}
     */
    function getId();
    
}
