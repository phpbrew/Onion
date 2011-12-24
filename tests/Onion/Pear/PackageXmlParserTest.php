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
namespace Onion\Pear;
class PackageXmlParserTest extends \PHPUnit_Framework_TestCase 
{
    function test() 
    {
        # spec from http://pear.php.net/manual/en/guide.developers.package2.dir.php
        $xmlstr =<<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<package>
    <name>PHP_Timer</name>
    <channel>pear.phpunit.de</channel>
    <summary>Utility class for timing</summary>
    <description>Utility class for timing</description>
    <lead>
        <name>Sebastian Bergmann</name>
        <user>sb</user>
        <email>sb@sebastian-bergmann.de</email>
        <active>yes</active>
    </lead>
    <date>2011-09-07</date>
    <time>11:38:14</time>
    <version>
        <release>1.0.2</release>
        <api>1.0.0</api>
    </version>
    <stability>
        <release>stable</release>
        <api>stable</api>
    </stability>
    <license>BSD License</license>
    <notes>
        http://github.com/sebastianbergmann/php-timer/blob/master/README.markdown
    </notes>
    <contents>
        <dir name="/">
            <dir name="examples">
                <file name="authors.php" role="doc"/>
            </dir>
            <dir name="HTML">
                <dir name="Template">
                    <file name="PHPLIB.php" role="php" />
                    <dir name="PHPLIB">
                        <file name="TEST.php" role="php" />
                    </dir>
                </dir>
            </dir>


        </dir>
        <dir name="/" baseinstalldir="HTML">
            <dir name="QuickForm">
                <file name="Element.php" role="php" />
                <!-- would be installed as HTML/QuickForm/Element.php -->
            </dir>
        </dir>
    </contents>
</package>
EOT;
        $p = new PackageXmlParser( $xmlstr );
        ok( $p );
        $list = $p->getContentFiles();

        var_dump( $list ); 

    }
}

