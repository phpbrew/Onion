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

    }
}
