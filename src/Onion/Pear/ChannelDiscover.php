<?php
namespace Onion\Pear;
use Exception;
use Onion\Downloader\CurlDownloader;
use Onion\Paths;
use CacheKit\FileSystemCache;

class ChannelDiscover 
{
    public $cache;

    public $downloaderClass = '\Onion\Downloader\CurlDownloader';

    public function __construct()
    {
        $this->cache = new FileSystemCache(array(
            'expiry' => 3600, // 1 hour
            'cache_dir' => Paths::cache_dir(),
        ));
    }

    public function getDownloader()
    {
        return new $this->downloaderClass;
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
                    if( $xmlstr = $downloader->fetch($httpUrl) )
                        break;
                } catch( Exception $e ) {
                    try {
                        if( $xmlstr = $downloader->fetch( $httpsUrl ) )
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



