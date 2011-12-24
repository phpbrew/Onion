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
namespace Onion\Pear;
use Exception;

/** 
 * pear channel package 
 * */
class Package 
{
    public $name;
    public $desc;
    public $summary;
    public $channel;
    public $license;

    /* last version */
    public $latest;

    /* last stable version */
    public $stable;

    /* last alpha version */
    public $alpha;

    /* last beta version */
    public $beta;

    /* all releases */
    public $releases = array();

    /**
     * dependencies
     */
    public $deps = array();

    public function __sleep()
    {
        return array('name','summary','channel','desc','license','latest','stable','alpha','beta','releases','deps');
    }

    public function __wakeup()
    {

    }

    public function toArray()
    {
        return (array) $this;

        $a = array('name','summary','channel','desc','license','latest','stable','alpha','beta','releases','deps');
        foreach( array() as $k ) {
            $a[ $k ] = $this->{ $k };
        }
        return $a;
    }
}

