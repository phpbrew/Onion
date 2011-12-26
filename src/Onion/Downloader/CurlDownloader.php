<?php
namespace Onion\Downloader;

use Exception;

class CurlDownloader 
    implements DownloaderInterface
{

    function progressCallback($downloadSize, $downloaded, $uploadSize, $uploaded)
    {
        // print progress bar
        $percent = ($downloaded > 0 ? (float) ($downloaded / $downloadSize) : 0.0 );
        $terminalWidth = 70;
        $sharps = (int) $terminalWidth * $percent;

        # echo "\n" . $sharps. "\n";
        echo "\r" . 
            str_repeat( '#' , $sharps ) . 
            str_repeat( ' ' , $terminalWidth - $sharps ) . 
            sprintf( ' %4d B %5d%%' , $downloaded , $percent * 100 );
    }

    function fetch($url)
    {
        $options = array();
        $defaults = array( 
            CURLOPT_HEADER => 0, 
            CURLOPT_URL => $url, 
            CURLOPT_FRESH_CONNECT => 1, 
            CURLOPT_RETURNTRANSFER => 1, 
            CURLOPT_FORBID_REUSE => 1, 
            CURLOPT_TIMEOUT => 10, 
        ); 
        $ch = curl_init(); 
        curl_setopt_array($ch, ($options + $defaults)); 

        $logger = \Onion\Application::getLogger();
        if( $logger->level > 6 ) {
            curl_setopt($ch, CURLOPT_NOPROGRESS, false);
            curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, array($this,'progressCallback'));
            curl_setopt($ch, CURLOPT_BUFFERSIZE, 32 );
        }

        if( ! $result = curl_exec($ch)) { 
            throw new Exception( $url . ":" . curl_error($ch) );
        }
        curl_close($ch); 

        if( $logger->level > 6 ) {
            echo "\n";
        }
        return $result;
    }
}
