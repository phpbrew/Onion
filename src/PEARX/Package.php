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

    public $deps = array();

    public $stable;

    public $alpha;

    public $beta;

    public $latest;



}


