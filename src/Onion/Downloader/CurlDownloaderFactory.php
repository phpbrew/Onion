<?php
namespace Onion\Downloader;
use CurlKit\CurlDownloader;
use CurlKit\Progress\ProgressBar;

class CurlDownloaderFactory 
{
    static function create($quiet = false) 
    {
        if( $quiet ) {
            return new CurlDownloader();
        }
        return new CurlDownloader( array( 
            'progress' => new ProgressBar
        ));
    }
}
