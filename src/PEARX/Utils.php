<?php
namespace PEARX;
use DOMDocument;
use Exception;

class Utils
{

    static function create_dom()
    {
        $dom = new DOMDocument('1.0');
        $dom->strictErrorChecking = false;
        $dom->preserveWhiteSpace = false;
        $dom->resolveExternals = false;
        return $dom;
    }

    static function load_xml($xml)
    {
        $dom = self::create_dom();
        if( false === $dom->loadXml( $xml ) ) {
            throw new Exception( "XML Document load failed." );
        }
        return $dom;
    }

}




