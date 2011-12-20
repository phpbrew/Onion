<?php
namespace Onion\Pear;


/**
 * A Simple Pear Channel data manipulator
 *
 */
class Channel
{

    /** 
     * channel name
     */
    public $name;

    /**
     * channel alias
     */
    public $alias;

    /**
     * channel summary
     */
    public $summary;

    /**
     * primary server
     */
    public $primary;

    /**
     * mirror servers
     *
     * @var array
     */
    public $mirrors = array();


    function __construct()
    {
        // $this->info = $info;
    }

    function getName()
    {
        return $this->name;
    }

    function getSummary()
    {
        return $this->summary;
    }

    function getAlias()
    {
        return $this->alias;
    }

    function getPrimaryServer()
    {
        return $this->primary;
    }

    function getMirrorServers()
    {
        return $this->mirrors;
    }

    function getRestBaseUrl()
    {
        $versions = array('REST1.3','REST1.2','REST1.1');
        foreach( $versions as $k ) {
            if( isset( $this->primary[ $k ] ) )
                return $this->primary[ $k ];
        }
    }

    function getAllPackages()
    {


    }

    function getPackage()
    {

    }

}


