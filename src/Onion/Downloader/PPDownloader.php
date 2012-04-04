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
namespace Onion\Downloader;

class PPDownloader 
    implements DownloaderInterface
{
    function request($url)
    {
        return file_get_contents($url);
    }
}

