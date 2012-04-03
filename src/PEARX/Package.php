<?php
namespace PEARX;

class Package
{

    public $name;

    public $summary;

    public $desc;

    /**
     * @var Channel
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
        $this->releases[] = (object) array( 
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

    public function getReleaseDistUrl($version, $extension = 'tgz' )
    {
        // xxx: save for https or http base url from channel discover object
        // return sprintf( 'http://%s/get/%s-%s.%s' , $this->channel, $this->name , $version , $extension );
        return sprintf('%s/get/%s-%s.%s',$this->channel->getBaseUrl(), $this->name , $version , $extension );
    }

    public function getLastReleaseDistUrl()
    {
        return $this->getReleaseDistUrl( $this->latest );
    }


}


