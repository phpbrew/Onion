<?php
/*
 * This file is part of the Onion package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Onion;

class SpecUtils 
{




    static function parseVersion($string)
    {
        $version_pattern = '([0-9.]+)';
        /* >= version */
        if( preg_match("/^
            \s*
            $version_pattern
            \s*
            \$/x",$string,$regs) ) 
        {
            return array( 'min' => $regs[1] ?: '0.0.0' );
        }
		elseif( preg_match("/^
            \s*
            [>=]+
            \s*
            $version_pattern
			\s*
			\$/x",$string,$regs) ) 
        {
			return array( 'min' => $regs[1] ?: '0.0.0' );
        }
		elseif( preg_match("/^
            \s*
            [<=]+
            \s*
            $version_pattern
			\s*
			\$/x",$string,$regs) ) 
        {
            return array( 'max' => $regs[1] );
        }
		elseif( preg_match("/^
            \s*
            $version_pattern
            \s*
            <=>
            \s*
            $version_pattern
            \s*
            \$/x", $string,$regs ) )
        {
            return array( 
                'min' => $regs[1] ?: '0.0.0',
                'max' => $regs[2],
            );
        }
    }

    static function parseAuthor($string)
    {
        $author = array();
        // parse author info:   {Name} ({Id}) <{email}>
        if( preg_match( '/^\s*
                    (.+?)
                    \s*
                    (?:"(\S+)"
                    \s*)?
                    <(\S+)>
                    \s*$/x' , $string , $regs ) ) 
        {
            if( count($regs) == 4 ) {
                list($orig,$name,$user,$email) = $regs;
                $author['name'] = $name;
                // $author['user'] = $user;
                $author['email'] = $email;
            }
            elseif( count($regs) == 3 ) {
                list($orig,$name,$email) = $regs;
                $author['name'] = $name;
                $author['email'] = $email;
            }
        }
        else {
            $author['name'] = $string;
        }
        return $author;
    }

}

