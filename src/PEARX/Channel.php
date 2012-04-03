<?php
namespace PEARX;

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

#          $parser = new ChannelParser;
#          $channel = $parser->parse( $xmlstr );
#          return $channel;
    }


    public function request($url)
    {
        if( $this->downloader ) {
            return $this->downloader->fetch( $url );
        }
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
        if( $xmlstr )
            return $xmlstr;

        $httpUrl = 'http://' . $host . '/channel.xml';
        $httpsUrl = 'https://' . $host . '/channel.xml';
        while( $this->retry-- ) {
            try {
                if( $xmlstr = $this->request($httpUrl) 
                    || $xmlstr = $this->request( $httpsUrl ) ) {
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


}


