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


    /**
     * Packages info
     *
     * Contains the information of packages of current channel.
     */
    public $packages = array();

    function __construct()
    {

    }

    function loadCache()
    {
        // XXX: try to load channel info from cache.
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


    function getAllPackages()
    {
        return $this->prefetchPackagesInfo();
    }



    // cache this.
    function prefetchPackagesInfo()
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
            $packagesInfo = $packagesInfoXml->getElementsByTagName('pi');
            foreach( $packagesInfo as $package ) {


                // build package information

                // echo $package->C14N();
                $p = $package->getElementsByTagName('p')->item(0);
                $packageName    = $p->getElementsByTagName('n')->item(0)->nodeValue;
                $packageSummary = $p->getElementsByTagName('s')->item(0)->nodeValue;
                $packageDesc    = $p->getElementsByTagName('d')->item(0)->nodeValue;
                $packageChannel = $p->getElementsByTagName('c')->item(0)->nodeValue;
                $packageLicense = $p->getElementsByTagName('l')->item(0)->nodeValue;


                $packageObj = new Package;
                $packageObj->name = $packageName;
                $packageObj->summary = $packageSummary;
                $packageObj->desc = $packageDesc;
                $packageObj->channel = $packageChannel;
                $packageObj->license = $packageLicense;
                $packageObj->releases = array();
                $packageObj->deps = array();

                $latestStable = 0;
                $latestAlpha = 0;
                $latestBeta = 0;
                $latest = 0;
                $releases = $package->getElementsByTagName('a')->item(0)->getElementsByTagName('r');
                foreach( $releases as $release ) {
                    $version = $release->getElementsByTagName('v')->item(0)->nodeValue;
                    $stability = $release->getElementsByTagName('s')->item(0)->nodeValue;

                    $packageObj->releases[ $version ] = $stability;

                    if( version_compare( $version , $latest ) === 1 ) {
                        $latest = $version;
                    }

                    switch( $stability ) {
                    case 'stable':
                        if( version_compare( $version , $latestStable ) === 1 ) {
                            $latestStable = $version;
                        }
                        break;
                    case 'alpha':
                        if( version_compare( $version , $latestAlpha ) === 1 ) {
                            $latestAlpha = $version;
                        }
                        break;
                    case 'beta':
                        if( version_compare( $version , $latestBeta ) === 1 ) {
                            $latestBeta = $version;
                        }
                        break;
                    }
                }

                $packageObj->stable = $latestStable;
                $packageObj->alpha = $latestAlpha;
                $packageObj->beta = $latestBeta;
                $packageObj->latest = $latest;


                $deps = $package->getElementsByTagName('deps');
                foreach( $deps as $dep ) {
                    $version = $dep->getElementsByTagName('v')->item(0)->nodeValue;
                    $depInfo = unserialize($dep->getElementsByTagName('d')->item(0)->nodeValue);

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
                    $packageObj->deps[ $version ] = $depInfo;

                    // XXX: build Resource Informations for fetching

                }
                // var_dump( $packageObj ); 
                $this->packages[ $packageObj->name ] = $packageObj;
            }

        }

        // save to channel object.
        return $this->packages;
    }

    function getPackage($name)
    {
        return $this->packages[ $name ];
    }

    function removePackage($name)
    {
        return unset($this->packages[$name]);
    }

    function __sleep()
    {
        return array( 'name' , 'alias' , 'summary' , 'primary' , 'mirrors' , 'packages' );
    }
}
