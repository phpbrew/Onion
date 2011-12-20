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
        return $this->mirroos;
    }

    function getRestBaseUrl()
    {
        $version = array('REST1.3','REST1.2','REST1.1');
    }

    function getAllPackages()
    {

    }

    function getPackage()
    {

    }

}


