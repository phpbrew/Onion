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
namespace Onion;


/**
 * main installer
 */
class Installer 
{
    public $manager;


    function __construct( \Onion\Dependency\DependencyManager $manager)
    {
        $this->manager = $manager;
    }

    function getLibraryInstaller()
    {
        return new Installer\LibraryInstaller;
    }

    function getPearInstaller()
    {
        return new Installer\PearInstaller;
    }

}


