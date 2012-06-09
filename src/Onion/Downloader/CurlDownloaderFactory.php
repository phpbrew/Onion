<?php
namespace Onion\Downloader;
use CurlKit\CurlDownloader;
use CurlKit\Progress\ProgressBar;

class CurlDownloaderFactory 
{
    static function create() 
    {
        $d = new CurlDownloader;
        $d->setProgressHandler( new ProgressBar );
        return $d;
    }
}
