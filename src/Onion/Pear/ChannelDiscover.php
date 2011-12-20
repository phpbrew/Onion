<?php
namespace Onion\Pear;
use Exception;
use Onion\Downloader\CurlDownloader;

class ChannelDiscover 
{
    public $downloaderClass = '\Onion\Downloader\CurlDownloader';

    function getDownloader()
    {
        return new $this->downloaderClass;
    }


    /**
     * lookup pear channel host
     *
     * @return Onion\Pear\Channel class
     */
    function lookup($pearhost)
    {
        $xmlstr = '';
        $downloader = $this->getDownloader();
        try {
            $url = 'http://' . $pearhost . '/channel.xml';
            $xmlstr = $downloader->fetch($url);
        } catch( Exception $e ) {
            $url = 'https://' . $pearhost . '/channel.xml';
            $xmlstr = $downloader->fetch($url);
        }


        $parser = new ChannelParser;
        $channel = $parser->parse( $xmlstr );
        return $channel;
    }

}



