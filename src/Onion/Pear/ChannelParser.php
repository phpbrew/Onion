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

class ChannelParser 
{

    function parse($xmlstr)
    {
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

