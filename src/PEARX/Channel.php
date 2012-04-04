<?php
namespace PEARX;
use PEARX\ChannelParser;
use DOMDocument;
use Exception;
use PEARX\Core;

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
    /**
     * @var string channel host name
     */
    public $name;

    /**
     * @var string suggestedalias
     */
    public $alias;


    /**
     * @var string summary
     */
    public $summary;

    /**
     * primary server
     */
    public $primary = array();

    public $rest; // Latest REST version




    public $cache;

    public $downloader;

    public $retry = 3;

    public $channelXml;

    /**
     * channel url scheme
     */
    public $scheme = 'http';

    public $core;

    public function __construct($host, $options = array() )
    {
        $this->core = new Core( $options );
        $this->channelXml = $this->fetchChannelXml( $host );

        $parser = new ChannelParser;
        $info = $parser->parse( $this->channelXml );

        $this->name = $info->name;
        $this->summary = $info->summary;
        $this->alias = $info->alias;
        $this->primary = $info->primary;
        $this->rest = $info->rest;
    }

    public function getBaseUrl()
    {
        return $this->scheme . '://' . $this->name . '/';
    }

    public function getRestBaseUrl($version = null)
    {
        if( $version && $this->primary[$version] )
            return $this->primary[ $version ];
        return $this->primary[ $this->rest ];
    }


    /**
     * fetch channel.xml from PEAR channel server.
     */
    public function fetchChannelXml($host)
    {
        $xmlstr = null;
        $xmlstr = $this->core->cache ? $this->core->cache->get( $host ) : null;

        // cache not found.
        if( null !== $xmlstr )
            return $xmlstr;

        $httpUrl = 'http://' . $host . '/channel.xml';
        $httpsUrl = 'https://' . $host . '/channel.xml';
        while( $this->retry-- ) {
            try {
                if( $xmlstr = $this->core->request($httpUrl)  ) {
                    $this->scheme = 'http';
                    break;
                }
                if( $xmlstr = $this->core->request( $httpsUrl ) ) {
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

    public function getCategories()
    {
        $baseUrl = $this->getRestBaseUrl();
        $url = $baseUrl . '/c/categories.xml';
        $xmlStr = $this->core->request($url);
        
        // libxml_use_internal_errors(true);
        $xml = Utils::create_dom();
        if( false === $xml->loadXml( $xmlStr ) ) {
            throw new Exception("Error in XMl document: $url");
        }

        $list = array();
        $nodes = $xml->getElementsByTagName('c');
        foreach ($nodes as $node) {
            // path like: /rest/c/Default/info.xml
            $link = $node->getAttribute("xlink:href");
            $name = $node->nodeValue;
            $category = new Category( $this, $name , $link );
            $list[] = $category;
        }
        return $list;
    }

    public function getPackages()
    {
        $packages = array();
        foreach( $this->getCategories() as $category ) {
            $packages[ $category->name ] = $category->getPackages();
        }
        return $packages;
    }

    public function findPackage($name)
    {
        foreach( $this->getCategories() as $category ) {
            $packages = $category->getPackages();
            if( isset($packages[$name]) )
                return $packages[ $name ];
        }
    }
}


