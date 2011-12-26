<?php
/*
 * This file is part of the {{ }} package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace tests\Onion\Downloader;
class DownloaderManagerTest extends \PHPUnit_Framework_TestCase 
{
    function test() 
    {
        $manager = new \Onion\Downloader\DownloaderManager;
        ok( $manager );
        ok( $d = $manager->createDownloader() );

        if( getenv('TEST_REMOTE') ) {
            $xml = $manager->download( 'http://pear.corneltek.com/channel.xml' );
            ok( $xml );
        }
    }

    function testCurl()
    {
        $curl = new \Onion\Downloader\CurlDownloader;
        $logger = \Onion\Application::getLogger();
        $logger->setVerbose();
        // get something large
        // $content = $curl->fetch('http://pear2.php.net/rest/c/PEAR2/packagesinfo.xml');
        // $content = $curl->fetch( 'http://localhost/src/B-C-1.31.tar.gz' );
    }
}
