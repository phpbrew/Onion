<?php
namespace PEARX;
use DOMDocument;

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


}




