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
        <dir name="/">
            <file md5sum="2b7116a9f3bf6f9e8cd914c81bf79830" name="lib/sfYaml.php" role="php" />
            <file md5sum="28196a0efdcea67d72e1eaf71e936958" name="lib/sfYamlDumper.php" role="php" />
            <file md5sum="cb97a7ac95e5c6df398514b43dfe9191" name="lib/sfYamlInline.php" role="php" />
            <file md5sum="02fc0966083c293c851f2de6abb24875" name="lib/sfYamlParser.php" role="php" />
            <file md5sum="872df9017f6485111a36d2ce9c1e8bdc" name="README.markdown" role="doc" />
            <file md5sum="4828aab1cb984d8a5d29e1c0df3cdcbd" name="LICENSE" role="doc" />
        </dir>
    </contents>
    <phprelease>
        <filelist>
            <install as="SymfonyComponents/YAML/sfYaml.php" name="lib/sfYaml.php" />
            <install as="SymfonyComponents/YAML/sfYamlDumper.php" name="lib/sfYamlDumper.php" />
            <install as="SymfonyComponents/YAML/sfYamlInline.php" name="lib/sfYamlInline.php" />
            <install as="SymfonyComponents/YAML/sfYamlParser.php" name="lib/sfYamlParser.php" />
        </filelist>
    </phprelease>
</package>
EOT;
        $p = new PackageXmlParser( $xmlstr );
        ok( $p );
        $list = $p->getContentFiles();

        ok( $list ); 

        ok( $list = $p->getContentFilesByRole( 'php' ));

        var_dump( $list ); 
        
        

    }
}

