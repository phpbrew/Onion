<?php
namespace Onion\Pear;
use Exception;

class ChannelDiscover 
{
    public $cache;

    public function __construct()
    {
        $this->cache = \Onion\Application::getInstance()->getCache();
    }

    public function getDownloader()
    {
        return new \CurlKit\CurlDownloader;
    }

    /**
     * lookup pear channel host
     *
     * @return Onion\Pear\Channel class
     */
    public function lookup($pearhost)
    {
        $xmlstr = null;
        $downloader = $this->getDownloader();

        $xmlstr = $this->cache->get( $pearhost );

        if( null === $xmlstr ) {
            $httpUrl = 'http://' . $pearhost . '/channel.xml';
            $httpsUrl = 'https://' . $pearhost . '/channel.xml';
            $retry = 3;
            while( $retry-- ) {
                try {
                    if( $xmlstr = $downloader->request($httpUrl) )
                        break;
                } catch( Exception $e ) {
                    try {
                        if( $xmlstr = $downloader->request( $httpsUrl ) )
                            break;
                    } 
                    catch( Exception $e ) {
                        throw new Exception("Channel discover failed: $pearhost");
                    }
                }
            }
            $this->cache->set($pearhost, $xmlstr );
        }

        $parser = new ChannelParser;
        $channel = $parser->parse( $xmlstr );
        return $channel;
    }

}



