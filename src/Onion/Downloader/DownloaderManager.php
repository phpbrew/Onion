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


    function __construct($select_default = true)
    {
        if( $select_default )
            $this->selectDefaultDownloader();
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
        $d = $this->getDownloader();
        return $d->fetch( $url );
    }

    function downloadXml($url)
    {
        if( extension_loaded('apc') ) {
            if( ($xmlstr = apc_fetch( $url ) ) ) {
                $xml = new DOMDocument();
                $xml->loadXML($xmlstr); 
                return $xml;
            }
        }

        $xmlstr = $this->download($url);

        if( extension_loaded('apc') ) {
            apc_store($url,$xmlstr);
        }

        // helper function to load xml
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
