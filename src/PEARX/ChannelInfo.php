<?php
namespace PEARX;

class ChannelInfo
{


    /**
     * @var string channel host name
     */
    public $name;

    /**
     * @var string suggestedalias
     */
    public $alias;


    /**
     * @var string summary
     */
    public $summary;

    /**
     * primary server
     */
    public $primary = array();

    public $rest; // Latest REST version


    public function getRestBaseUrl($version = null)
    {
        if( $version && $this->primary[$version] )
            return $this->primary[ $version ];
        return $this->primary[ $this->rest ];
    }


}



