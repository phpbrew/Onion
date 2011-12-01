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


	/* 
	 *
	 * format 1:
	 *		channel/pkg name = version expression 
	 *
	 * format 2:
	 *
	 *		pkg name = {URI} resources
	 *
	 * format 3:
	 *		pkg name = {VCS};{URI};{branch or revision}
	 *
	 * */
	static function detectDependency($key,$value = null)
	{
		if( in_array($key, array('pearinstaller','php') ) ) {
			return 'core';
		}

		// support extension/{extension name} = {version expression}
		if( preg_match('/^ext(?:ension)?\/\w+/',$key) )
			return 'extension';

		// otherwisze it's package
		return 'package';
	}


	/*
	 * returned types: package, package.uri, package.vcs
	 *
	 */
	static function parseDependency($key,$value)
	{
		// format:  {channel domain}/{package name} = {version expression}
		if( preg_match('/^([a-zA-Z0-9.]+)\/(\w+)$/' , $key , $regs ) ) 
		{
			return array(
				'type'    => 'package',
				'channel' => $regs[1],
				'name'    => $regs[2],
				'version' => self::parseVersion($value),
			);
		}
		elseif( preg_match('/^ext(?:ension)?\/(\w+)$/',$key,$regs) ) {
			return array(
				'type'    => 'extension',
				'name'    => $regs[1],
				'version' => self::parseVersion($value),
			);
		}
		elseif( preg_match('/^(\w+)$/',$key,$regs) ) 
		{

			// package with URI format
			if( preg_match('/^https?:\/\//',$value) ) {
				return array(
					'type' => 'package.uri',
					'name' => $key,
					'uri'  => $value,
				);
			}
			// NOTE: this is not supported in package.xml 2.0
			elseif( preg_match('/^(git|svn):(\S+)$/',$value,$regs) ) {
				return array( 
					'type' => 'package.vcs',
					'name' => $key,
					'vcs' => $regs[1],
					'uri' => $regs[2],
				);
			}
			elseif( stripos($value,'conflicts') ) {
				return array(
					'type' => 'package.conflicts',
					'name' => $key,
				);
			}

		}
	}

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
                $author['user'] = $user;
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

