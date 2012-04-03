<?php

class CurlDownloaderTest extends PHPUnit_Framework_TestCase
{
    function test()
    {
        $downloader = new CurlKit\CurlDownloader;
        ok( $downloader );

        $content = $downloader->request( 'http://github.com' );
        ok( $content );
    }
}


