<?php
namespace Onion\Pear;
use Onion\Downloader\DownloaderManager;
use DOMDocument;

/**
 * A Simple Pear Channel data manipulator
 *
 */
class Channel
{

    /** 
     * channel name
     */
    public $name;

    /**
     * channel alias
     */
    public $alias;

    /**
     * channel summary
     */
    public $summary;

    /**
     * primary server
     */
    public $primary;

    /**
     * mirror servers
     *
     * @var array
     */
    public $mirrors = array();


    function __construct()
    {
        // $this->info = $info;
    }

    function getName()
    {
        return $this->name;
    }

    function getSummary()
    {
        return $this->summary;
    }

    function getAlias()
    {
        return $this->alias;
    }

    function getPrimaryServer()
    {
        return $this->primary;
    }

    function getMirrorServers()
    {
        return $this->mirrors;
    }

    function getRestBaseUrl($version = null)
    {
        if( $version && $this->primary[$version] )
            return $this->primary[ $version ];

        $versions = array('REST1.3','REST1.2','REST1.1');
        foreach( $versions as $k ) {
            if( isset( $this->primary[ $k ] ) )
                return $this->primary[ $k ];
        }
    }



    // cache this.
    function getAllPackages()
    {
        $dm = new DownloaderManager;

        $restBaseurl = $this->getRestBaseUrl();
        $uriComp = parse_url( $restBaseurl );

        $baseurl = $uriComp['scheme'] . '://' . $uriComp['host'];

        $categoryXml = $dm->downloadXml($restBaseurl . "/c/categories.xml");
        $categories = $categoryXml->getElementsByTagName("c");
        foreach ($categories as $category) {

            // path like: /rest/c/Default/info.xml
            $categoryLink = $category->getAttribute("xlink:href");
            $categoryLink = str_replace("info.xml", "packagesinfo.xml", $categoryLink);
            $packagesInfoXml = $dm->downloadXml( $baseurl . '/' . $categoryLink);
            $packages = $packagesInfoXml->getElementsByTagName('pi');

            foreach( $packages as $package ) {
                // echo $package->C14N();
                $p = $package->getElementsByTagName('p')->item(0);
                $packageName    = $p->getElementsByTagName('n')->item(0)->nodeValue;
                $packageSummary = $p->getElementsByTagName('s')->item(0)->nodeValue;
                $packageDesc    = $p->getElementsByTagName('d')->item(0)->nodeValue;
                $packageChannel = $p->getElementsByTagName('c')->item(0)->nodeValue;
                $packageLicense = $p->getElementsByTagName('l')->item(0)->nodeValue;

                $releases = $package->getElementsByTagName('a')->item(0)->getElementsByTagName('r');
                foreach( $releases as $release ) {
                    $version = $release->getElementsByTagName('v')->item(0)->nodeValue;
                    $stability = $release->getElementsByTagName('s')->item(0)->nodeValue;

                    // var_dump( $version , $stability ); 
                }

                $deps = $package->getElementsByTagName('deps');
                foreach( $deps as $dep ) {
                    $version = $dep->getElementsByTagName('v')->item(0)->nodeValue;
                    $depInfo = unserialize($dep->getElementsByTagName('d')->item(0)->nodeValue);
                    var_dump( $depInfo ); 

                    /*
                     * depInfo structure:
                        array(1) {
                            ["required"]=>
                            array(2) {
                                ["php"]=>
                                array(1) {
                                ["min"]=>
                                string(3) "5.3"
                                }
                                ["pearinstaller"]=>
                                array(1) {
                                ["min"]=>
                                string(3) "1.4"
                                }
                            }
                        }
                    */

                }

            }

        }

        // $url = $baseurl . '/p/packages.xml';
        // var_dump( $xml ); 
    }

    function getPackage()
    {

    }

}


