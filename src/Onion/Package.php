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

class Package
{
    public $name;
    public $version;
    public $desc;
    public $summary;
    public $stability;

    /** 
     * ConfigContainer object
     */
    public $config;

    public function getResource($packageName)
    {
        if( $config->has( 'resource ' . $packageName ) )
            return $config->get( 'resource ' . $packageName );
    }



}



