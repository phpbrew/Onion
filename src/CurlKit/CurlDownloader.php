<?php
namespace CurlKit;

/**
 * $downloader = new CurlKit/CurlDownloader;
 * $downloader->setBasicCredential( 'user', 'password' );
 * $downloader->setProxy( 'user', 'password' );
 * $downloader->setProgress( new StarProgress );
 *
 * // Get request
 * $downloader->request( 'http://.....' , array(
 *      'param' => 1,
 *      'param' => 2,
 * ));
 *
 * // Post request
 * $downloader->post( 'http://....' , array( 
 * $downloader->requestXml( 'http://.....' , array( ... ) );
 *
 * ));
 */
use Exception;

class CurlDownloader 
{

    public $options = array( 
        CURLOPT_HEADER => 0, 
        CURLOPT_FRESH_CONNECT => 1, 
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_FORBID_REUSE => 1, 
        CURLOPT_TIMEOUT => 10, 
        CURLOPT_BUFFERSIZE => 64,
    );

    public function setOption($key,$value) 
    {
        $this->options[ $key ] = $value;
    }

    public function newCurlResource( $extra = array() ) 
    {
        $ch = curl_init(); 
        curl_setopt_array($ch, ($this->options + $extra )); 
        return $ch;
    }

    /**
     * Set progress handler
     *
     * @param $callback
     */
    public function setProgressHandler( $handler ) 
    {
        $this->options[ CURLOPT_NOPROGRESS ] = false;
        $this->options[ CURLOPT_PROGRESSFUNCTION ] = array($handler,'callback');
    }

    public function getProgressHandler()
    {
        if( isset($this->options[ CURLOPT_PROGRESSFUNCTION ]) )
            return $this->options[ CURLOPT_PROGRESSFUNCTION ];
    }

    public function request($url, $params = array() , $options = array() ) 
    {
        $options[ CURLOPT_URL ] = $url;
        $ch = $this->newCurlResource( $options );
        if( ! $result = curl_exec($ch)) { 
            throw new Exception( $url . ":" . curl_error($ch) );
        }
        curl_close($ch); 
        return $result;
    }

}

