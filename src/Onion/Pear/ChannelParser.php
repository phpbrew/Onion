<?php
/*
 * This file is part of the Onion package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Onion\Pear;
use Onion\Pear\Channel;
use SimpleXMLElement;
use Exception;

class ChannelParser 
{


    function parse($arg)
    {
        $xmlstr = null;
        if( strpos($arg,'<?xml') === 0 ) {
            $xmlstr = $arg;
        } elseif( is_file($arg) ) {
            $xmlstr = file_get_contents($arg);
        } else {
            throw new Exception("Unexpectedd argument for channel parser.");
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
        return $channel;
    }

}

