<?php
namespace Onion\Downloader;

class PPDownloaderFactory 
{
    static function create()
    {
        $d = new PPDownloader;
        return $d;
    }
}
