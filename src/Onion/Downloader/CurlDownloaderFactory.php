<?php
namespace Onion\Downloader;
use CurlKit\CurlDownloader;

class CurlDownloaderFactory 
{
    static function create() 
    {
        $d = new CurlDownloader;
        $d->setProgressHandler( new \CurlKit\ProgressBar );
        return $d;
    }
}
