<?php
namespace Onion\Pear;

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

    }

    function getSummary()
    {

    }

    function getSuggestedAlias()
    {

    }

    function getServers()
    {

    }

    function getRestBaseUrl()
    {

    }

    function getAllPackages()
    {

    }

    function getPackage()
    {

    }

}


