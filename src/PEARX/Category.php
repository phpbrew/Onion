<?php
namespace PEARX;

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

            // var_dump( $link , $node->nodeValue ); 
            // new Category( $node->nodeValue );
            // $categoryLink = str_replace("info.xml", "packagesinfo.xml", $categoryLink);
            // $packagesInfoXml = $dm->downloadXml( $baseurl . '/' . $categoryLink);
            // $packagesInfo = $packagesInfoXml->getElementsByTagName('pi');
            // foreach( $packagesInfo as $package ) { }
    }

    public function fetchInfoXml()
    {
        $xmlstr = $this->channel->request( $this->infoUrl );
        $this->infoXml = $this->channel->createDOM();
        $this->infoXml->loadXml( $xmlstr );
        // XXX:
    }

    public function getPackages()
    {
        $xmlstr = $this->channel->request( $this->packagesInfoUrl );
        $this->packagesInfoXml = $this->channel->createDOM();
        $this->packagesInfoXml->loadXml( $xmlstr );

    }

}


