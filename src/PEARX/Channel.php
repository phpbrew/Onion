<?php
namespace PEARX;
use PEARX\ChannelParser;
use DOMDocument;
use Exception;

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

    public function getBaseUrl()
    {
        return $this->scheme . '://' . $this->info->name . '/';
    }

    public function getRestBaseUrl()
    {
        return $this->info->getRestBaseUrl();
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

    public function createDOM()
    {
        $xml = new DOMDocument('1.0');
        $xml->strictErrorChecking = false;
        $xml->preserveWhiteSpace = false;
        $xml->resolveExternals = false;
        return $xml;
    }

    public function fetchCategories()
    {
        $baseUrl = $this->info->getRestBaseUrl();
        $url = $baseUrl . '/c/categories.xml';
        $xmlStr = $this->request($url);
        
        // libxml_use_internal_errors(true);
        $xml = $this->createDOM();
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
    }

    public function fetchAllPackages()
    {


    }


}


