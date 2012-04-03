<?php
namespace PEARX;

class Core
{
    public $downloader;
    public $cache;

    function __construct( $options = array() )
    {
        if( isset($options['cache']) ) {
            $this->cache = $options['cache'];
        }

        if( isset($options['downloader']) ) {
            $this->downloader = $options['downloader'];
        }

        if( isset($options['retry']) ) {
            $this->retry = $options['retry'];
        }
    }

    public function request($url)
    {
        if( $this->downloader ) {
            return $this->downloader->fetch( $url );
        }
        ini_set('default_socket_timeout', 120);
        return file_get_contents($url);
    }


}


