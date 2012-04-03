<?php
namespace PEARX;

class Package
{

    public $name;

    public $summary;

    public $desc;

    /**
     * @var string channel
     */
    public $channel;


    /**
     * @var string license
     */
    public $license;

    public $releases = array();

    public $versions = array();

    public $deps = array();

    public $stable;

    public $alpha;

    public $beta;

    public $latest;

    public function addRelease( $version , $stability )
    {
        $this->releases[] = array( 
            'version' => $version,
            'stability' => $stability,
        );
        $this->versions[ $version ] = $stability;
    }

    public function getRelease( $version ) 
    {
        if( isset( $this->versions[ $version ] ) )
            return $this->versions[ $version ];
    }

}


