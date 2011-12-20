<?php
namespace Onion\Pear;
use Exception;
use Onion\Downloader\CurlDownloader;
use Onion\Pear\Channel;
use SimpleXMLElement;

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

        // build channel object.
        $channel = new Channel;

        $xml = new SimpleXMLElement($xmlstr);

        $channel->name = (string) $xml->name;
        $channel->summary = (string) $xml->summary;
        $channel->alias = (string) $xml->suggestedalias;

        // build primary server section
        $channel->primary = array();
        foreach( $xml->servers->primary->rest->baseurl as $element ) {
            $attrs = $element->attributes();
            $channel->primary[ (string) $attrs->type ] = (string) $element;
        }

        // XXX: support mirrors 
        // var_dump( $channel );
        return $xml;
    }

}



