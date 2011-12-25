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
namespace Onion\Pear\PackageXml;

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

    function getInstallAs()
    {
        if( $this->installAs )
            return $this->installAs;
        return $this->file;
    }
}
