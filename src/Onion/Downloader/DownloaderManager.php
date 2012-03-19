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
namespace Onion\Downloader;

use Onion\Downloader\CurlDownloader;
use Onion\Downloader\DownloaderInterface;
use DOMDocument;


/**
 * Detect appropriate downloader for package, file download.
 *
 */
class DownloaderManager 
{

    /**
     * downloader object, if user forced a custom downloader 
     *
     * @var \Onion\Downloader\DownloaderInterface
     */
    public $downloader;

    /**
     * default downloader class
     *
     * @var string
     */
    public $downloaderClass;

    public $logger;

    public $cache;

    function __construct($select_default = true)
    {
        if( $select_default )
            $this->selectDefaultDownloader();
        $this->logger = \Onion\Application::getInstance()->getLogger();
        $this->cache  = \Onion\Application::getInstance()->getCache();
    }

    function selectDefaultDownloader()
    {
        // we select curl first because it's faster.
        if( extension_loaded('curl') ) {
            $this->downloaderClass = '\Onion\Downloader\CurlDownloader';
        }
        else {
            $this->downloaderClass = '\Onion\Downloader\PPDownloader';
        }
    }

    function createDownloader()
    {
        if( ! $this->downloaderClass )
            throw new Exception("Downloader Class is not defined.");
        return new $this->downloaderClass;
    }

    function getDownloader()
    {
        if( $this->downloader )
            return $this->downloader;
        return $this->createDownloader();
    }

    function setDownloaderClass($class)
    {
        $this->downloaderClass = $class;
    }

    function download($url)
    {
        $content = $this->cache->get($url);
        if( null === $content ) {
            $this->logger->debug2( "Fetching $url ..." , 1 );
            $d = $this->getDownloader();
            $content = $d->fetch( $url );
            $this->cache->set( $url, $content );
        }
        return $content;
    }

    function downloadXml($url)
    {
        $xmlstr = $this->cache->get( $url );
        if( null === $xmlstr ) {
            $xmlstr = $this->download($url);
            $this->cache->set( $url, $xmlstr );
        }
        $xml = new DOMDocument();
        $xml->loadXML($xmlstr); 
        return $xml;
    }

    /**
     * for singleton instance 
     */
    static function getInstance()
    {
        static $instance;
        if( $instance )
            return $instance;
        return $instance = new self;
    }

}
