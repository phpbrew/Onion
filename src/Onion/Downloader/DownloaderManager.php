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
use DOMDocument;
use Onion\Downloader\DownloaderInterface;
use CurlKit\CurlDownloader;

/**
 * Detect appropriate downloader for package, file download.
 */
class DownloaderManager 
{

    /**
     * default downloader class
     *
     * @var string
     */
    public $downloaderFactory;

    public $logger;

    public $cache;

    function __construct()
    {
        $this->logger = \Onion\Application::getInstance()->getLogger();
        $this->cache  = \Onion\Application::getInstance()->getCache();
        if( extension_loaded('curl') )
            $this->downloaderFactory = 'Onion\Downloader\CurlDownloaderFactory';
        else
            $this->downloaderFactory = 'Onion\Downloader\PPDownloaderFactory';
    }

    public function getDownloader()
    {
        $class = $this->downloaderFactory;
        return $class::create();
    }

    public function download($url)
    {
        $content = $this->cache->get($url);
        if( null === $content ) {
            $this->logger->debug2( "Fetching $url ..." , 1 );
            $d = $this->getDownloader();
            $content = $d->request( $url );
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
