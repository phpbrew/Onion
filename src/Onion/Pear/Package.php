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
use Onion\Package\PackageInterface;
use Exception;

/** 
 * pear channel package
 * */
class Package 
    implements PackageInterface
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

    public function getId()
    {
        return $this->name;
    }

    public function __sleep()
    {
        return array('name','summary','channel','desc','license','latest','stable','alpha','beta','releases','deps');
    }

    public function __wakeup()
    {

    }

    public function getLastDistUrl()
    {
        return $this->getDistUrlByVersion( $this->latest );
    }

    public function getDistUrlByVersion(string $version, $extension = 'tgz' )
    {
        // xxx: save for https or http base url from channel discover object
        return sprintf( 'http://%s/get/%s-%s.%s' , $this->channel, $this->name , $version , $extension );
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

