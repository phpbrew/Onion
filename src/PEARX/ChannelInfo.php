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

}



