<?php
namespace PEARX;
use SimpleXMLElement;
use Exception;

/**
 * PEARX Channel Parser
 *
 *    $parser = new ChannelParser;
 *    $parser->parse( $xml );
 *    $parser->parse( $xmlurl );
 *    $parser->parse( $file );
 *
 *
 */

class ChannelParser 
{

    function parse($arg)
    {
        $xmlstr = null;
        if( strpos($arg,'<?xml') === 0 ) {
            $xmlstr = $arg;
        } elseif( is_file($arg) || preg_match('#^https?://#',$arg) ) {
            $xmlstr = file_get_contents($arg);
        } else {
            throw new Exception("Unexpectedd argument for channel parser.");
        }

        // build channel info object.
        $channel = new ChannelInfo;
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
        return $channel;
    }

}

