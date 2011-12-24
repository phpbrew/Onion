<?php
namespace Onion\Downloader;

class CurlDownloader 
    implements DownloaderInterface
{
    function fetch($url)
    {
        echo "Fetch $url\n";
        
        $options = array();
        $defaults = array( 
            CURLOPT_HEADER => 0, 
            CURLOPT_URL => $url, 
            CURLOPT_FRESH_CONNECT => 1, 
            CURLOPT_RETURNTRANSFER => 1, 
            CURLOPT_FORBID_REUSE => 1, 
            CURLOPT_TIMEOUT => 4, 
        ); 
        $ch = curl_init(); 
        curl_setopt_array($ch, ($options + $defaults)); 
        if( ! $result = curl_exec($ch)) 
        { 
            trigger_error(curl_error($ch));
        }
        curl_close($ch); 
        return $result;
    }
}


/*
 *
    function callback($download_size, $downloaded, $upload_size, $uploaded)
    {
        // do your progress stuff here
    }

    $ch = curl_init('http://www.example.com');

    // This is required to curl give us some progress
    // if this is not set to false the progress function never
    // gets called
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);

    // Set up the callback
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'callback');

    // Big buffer less progress info/callbacks
    // Small buffer more progress info/callbacks
    curl_setopt($ch, CURLOPT_BUFFERSIZE, 128);

    $data = curl_exec($ch);
*/
