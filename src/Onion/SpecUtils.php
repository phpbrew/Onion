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
            return array( 'min' => $regs[1] );
        }
		elseif( preg_match("/^
            \s*
            [>=]+
            \s*
            $version_pattern
			\s*
			\$/x",$string,$regs) ) 
        {
            return array( 'min' => $regs[1] );
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
                'min' => $regs[1],
                'max' => $regs[2],
            );
        }
    }

}

