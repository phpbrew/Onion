<?php
namespace Onion;

/**
 * Structrual INI format parser
 *
 * autoload = { 
 *    Assetic => 'src'
 *    Key => {
 *       Key => Value
 *    }
 * }
 *
 */

class ParseErrorException extends Exception { }



class INIParser
{
    public $stash = array();

    function parseHash($lines,& $start) {

    }

    function parse($file)
    {
        $content = file_get_contents($file);
        $lines = explode("\n",$content);
        $i = 0;
        $stash = array();
        $section = null;
        $subsection = null;
        while(1) {
            $line = $lines[$i];
            if( preg_match( '/^\[([^\]]+)\]/', $line, $regs ) ) {
                $section = $regs[1];
                $i++;
                continue;
            }
            elseif( preg_match('/^  ([^=]+)  \s*  =  \s*  ([{"])  /x', $line, $regs) ) {
                $key = $regs[1];
                $attributeType = $regs[2];
                switch( $attributeType ) {
                case '{':
                    // hash 
                    break;
                case '"':
                    // string
                    break;
                }

                // parse subsection

            }
        }
        return $stash;
    }
}



