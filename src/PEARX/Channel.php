<?php
namespace PEARX;
use PEARX\ChannelParser;

/**
 * $channel = new PEARX\Channel( 'pear.php.net', array(
 *    'cache' => ....
 * ));
 *
 * $channel->getPackages();
 *
 */
class Channel
{
    public $cache;

    public $downloader;

    public $retry = 3;

    public $channelXml;

    /**
     * Channel Info object
     */
    public $info;


    /**
     * channel url scheme
     */
    public $scheme = 'http';

    public function __construct($host, $options = array() )
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

        $this->channelXml = $this->fetchChannelXml( $host );

        $parser = new ChannelParser;
        $this->info = $parser->parse( $this->channelXml );
    }


    public function request($url)
    {
        if( $this->downloader ) {
            return $this->downloader->fetch( $url );
        }
        ini_set('default_socket_timeout', 120);
        return file_get_contents($url);
    }

    /**
     * fetch channel.xml from PEAR channel server.
     */
    public function fetchChannelXml($host)
    {
        $xmlstr = null;
        $xmlstr = $this->cache ? $this->cache->get( $host ) : null;

        // cache not found.
        if( null !== $xmlstr )
            return $xmlstr;

        $httpUrl = 'http://' . $host . '/channel.xml';
        $httpsUrl = 'https://' . $host . '/channel.xml';
        while( $this->retry-- ) {
            try {
                if( $xmlstr = $this->request($httpUrl)  ) {
                    $this->scheme = 'http';
                    break;
                }
                if( $xmlstr = $this->request( $httpsUrl ) ) {
                    $this->scheme = 'https';
                    break;
                }
            } catch( Exception $e ) {
                fwrite( STDERR , "PEAR Channel discover failed: $host\n" );
                fwrite( STDERR , $e->getMessage(), "\n" );
            }
        }

        if( ! $xmlstr ) {
            throw new Exception('channel.xml fetch failed.');
        }

        // save cache
        if( $this->cache ) {
            $this->cache->set($host, $xmlstr );
        }
        return $xmlstr;
    }

    public function getRestBaseUrl($version = null)
    {
        if( $version && $this->info->primary[$version] )
            return $this->info->primary[ $version ];

        $versions = array('REST1.3','REST1.2','REST1.1');
        foreach( $versions as $k ) {
            if( isset( $this->info->primary[ $k ] ) )
                return $this->info->primary[ $k ];
        }
    }





}


