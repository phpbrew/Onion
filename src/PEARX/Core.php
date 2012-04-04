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

    public function request($url, $force = false)
    {
        if( false == $force && $this->cache && $content = $this->cache->get( $url ) ) {
            if( $content )
                return $content;
        }

        if( $this->downloader ) {
            $content = $this->downloader->fetch( $url );
        }
        ini_set('default_socket_timeout', 120);
        $content = file_get_contents($url);

        if( $this->cache ) {
            $this->cache->set( $url , $content );
        }
        return $content;
    }


}


