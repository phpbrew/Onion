<?php
namespace PEARX;
use PEARX\Utils;

class Category
{
    // Channel object
    public $channel;

    // Category name
    public $name;

    public $infoUrl;

    public $packagesInfoUrl;

    public $infoXml;

    public $packagesInfoXml;

    public function __construct($channel,$name,$infoPath)
    {
        $this->channel = $channel;
        $this->name = $name;
        $this->infoUrl = $this->channel->getBaseUrl() . $infoPath;
        $this->packagesInfoUrl = str_replace('info.xml', 'packagesinfo.xml', $this->channel->getBaseUrl() . $infoPath );
    }

    public function fetchInfoXml()
    {
        $xmlstr = $this->channel->core->request( $this->infoUrl );
        $this->infoXml = Utils::create_dom();
        $this->infoXml->loadXml( $xmlstr );
        // XXX:
    }

    public function getPackages()
    {
        $xmlstr = $this->channel->core->request( $this->packagesInfoUrl );

        $xml = Utils::create_dom();
        if( false === $xml->loadXml( $xmlstr ) ) {
            throw new Exception( "Package Info XML load failed: " . $this->packagesInfoUrl );
        }


        $this->packagesInfoXml = $xml;

        $packageNodes = $xml->getElementsByTagName('pi');
        $packages = array();
        foreach( $packageNodes as $node ) {
            $package = new Package;

            $p = $node->getElementsByTagName('p')->item(0);
            $package->name    = $p->getElementsByTagName('n')->item(0)->nodeValue;
            $package->summary = $p->getElementsByTagName('s')->item(0)->nodeValue;
            $package->desc    = $p->getElementsByTagName('d')->item(0)->nodeValue;
            $package->channel = $p->getElementsByTagName('c')->item(0)->nodeValue;
            $package->license = $p->getElementsByTagName('l')->item(0)->nodeValue;

            $depsList = $node->getElementsByTagName('deps');
            foreach( $depsList as $depsNode ) {
                $v = $depsNode->getElementsByTagName('v')->item(0)->nodeValue;// version string
                $d = unserialize($depsNode->getElementsByTagName('d')->item(0)->nodeValue); // dependency hash
                $package->deps[$v] = $d;
            }

            $latestStable = 0;
            $latestAlpha = 0;
            $latestBeta = 0;
            $latest = 0;
            $releases = $node->getElementsByTagName('a')->item(0)->getElementsByTagName('r');
            foreach( $releases as $release ) {
                $version = $release->getElementsByTagName('v')->item(0)->nodeValue;
                $stability = $release->getElementsByTagName('s')->item(0)->nodeValue;

                $package->addRelease( $version , $stability );

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

            $package->stable = $latestStable;
            $package->alpha = $latestAlpha;
            $package->beta = $latestBeta;
            $package->latest = $latest;

            $packages[ $package->name ] = $package;
        }
        return $packages;
    }

}


