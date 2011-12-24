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
namespace Onion\Pear;
use SimpleXMLElement;
use Exception;

class ContentFile
{
    /**
     * follows the spec of http://pear.php.net/manual/en/guide.developers.package2.contents.php
     */
    public $file;
    public $installAs;
    public $role;

    function __construct($file)
    {
        $this->file = $file;
    }

}

class PackageXmlParser
{
    public $xml;

    function __construct($arg)
    {
        if( strpos($arg,'<?xml') === 0 ) {
            $this->xml = new SimpleXMLElement( $arg );
        }
        elseif( file_exists($arg) ) {
            $this->xml = new SimpleXMLElement( file_get_contents( $arg ) );
        }
        else  {
            throw new Exception;
        }
    }



    /**
     * need to support base installdir
     */
    function traverseContents($children, $parentPath = null )
    {
        $files = array();
        foreach( $children as $node ) {
            if( $node->getName() == 'dir' ) {
                $dirname = $node['name'];
                $baseInstallDir = @$node['baseinstalldir'];

                $dirpath = $parentPath;
                if( $dirname != '/' )
                    $dirpath .= $dirname;

                // $dirpath = $parentPath ? $parentPath . DIRECTORY_SEPARATOR . $dirname : $dirname;
                if( $baseInstallDir )
                    $dirpath .= $baseInstallDir . DIRECTORY_SEPARATOR;

                if( $dirname != '/' )
                    $dirpath .= DIRECTORY_SEPARATOR;

                $subfiles = $this->traverseContents( $node->children(), $dirpath );
                $files = array_merge( $files , $subfiles );
            }
            elseif( $node->getName() == 'file' ) {
                $filename       = (string) $node['name'];
                $installAs      = (string) @$node['install-as'];
                $baseInstallDir = (string) @$node['baseinstalldir'];
                $role           = (string) @$node['role'];
                $md5sum         = (string) @$node['md5sum'];

                $file = null;
                if( $baseInstallDir ) {
                    $file = new ContentFile( $parentPath . $baseInstallDir . DIRECTORY_SEPARATOR . $filename );
                }
                else {
                    $file = new ContentFile( $parentPath . $filename );
                }
                if( $installAs )
                    $file->installAs = $installAs;

                $file->role = $role;
                $file->md5sum = $md5sum;
                $files[] = $file;
            }
        }
        return $files;
    }

    function getPhpReleaseFileList()
    {

    }

    function getContentFilesByRole($role)
    {
        $files = $this->getContentFiles();
        return array_filter( $files , function($item) use ($role) { 
            return $item->role == $role;
        });
    }

    function getContentFiles()
    {
        $xml = $this->xml;
        $contents = $xml->contents;
        $children = $contents->children();
        return $this->traverseContents( $children );
    }




}

