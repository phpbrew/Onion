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
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_FORBID_REUSE => 1, 
        CURLOPT_BUFFERSIZE => 64,
    );

    public $refreshConnect = 1;
    public $followLocation = 1;
    public $timeout = 30;

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function newCurlResource( $extra = array() ) 
    {
        $ch = curl_init(); 
        curl_setopt_array($ch, (
            $this->options 
                + array( 
                    CURLOPT_FRESH_CONNECT => $this->refreshConnect,
                    CURLOPT_FOLLOWLOCATION => $this->followLocation,
                    CURLOPT_TIMEOUT => $this->timeout,
                ) 
                + $extra
        )); 
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

    public function fetch($url)
    {
        return $this->request( $url );
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

    // curl_setopt($s,CURLOPT_MAXREDIRS,$this->_maxRedirects); 
    // curl_setopt($s,CURLOPT_FOLLOWLOCATION,$this->_followlocation); 
    // curl_setopt($tuCurl, CURLOPT_POST, 1); 
    // curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $data); 
    
    // curl_setopt($s,CURLOPT_COOKIEJAR,$this->_cookieFileLocation); 
    // curl_setopt($s,CURLOPT_COOKIEFILE,$this->_cookieFileLocation); 


    // basic auth
    // curl_setopt($s, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass); 
    //
    // get info
    // $this->_status = curl_getinfo($s,CURLINFO_HTTP_CODE); 
    
}

