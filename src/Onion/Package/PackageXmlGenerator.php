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
namespace Onion\Package;

use Onion\LoggableInterface;

/**
 * Generate package.xml from an package
 *
 *
 *      $pkgxml = new PackageXmlGenerator( $pkginfo );
 *      $pkgxml->setLogger( $logger );
 *      $pkgxml->setUseDefault(true);
 *      $pkgxml->setReformat(true);
 *      $pkgxml->generate('package.xml');
 * 
 */
class PackageXmlGenerator
    implements LoggableInterface
{
    public $package;
    public $reformat = true;
    public $useDefault = true;

    function __construct( $package )
    {
        $this->package = $package;
    }

    function setUseDefault($bool)
    {
        $this->useDefault = $bool;
    }

    function setReformat($bool)
    {
        $this->reformat = $bool;
    }

    function setLogger($logger)
    {
        $this->logger = $logger;
    }

    function getLogger()
    {
        return $this->logger;
    }


    function generate($file)
    {

    }

}


