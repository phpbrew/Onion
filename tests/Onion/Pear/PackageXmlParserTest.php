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
        <dir name="/">
            <file install-as="Onion/Application.php" name="src/Onion/Application.php" role="php" md5sum="cd08c9e1a21ce6d2f7ef0c0fc2ed849f"/>
            <file install-as="Onion/Command/BuildCommand.php" name="src/Onion/Command/BuildCommand.php" role="php" md5sum="c6c609136de4828b2f4e9d1624dbfd6c"/>
            <file install-as="Onion/Command/BundleCommand.php" name="src/Onion/Command/BundleCommand.php" role="php" md5sum="aa7a8d364e4d0f2ab041571e2f4e5fc8"/>
            <file install-as="Onion/Command/CompileCommand.php" name="src/Onion/Command/CompileCommand.php" role="php" md5sum="3bd78385317b655917bd82c9a1cb35af"/>
            <file install-as="Onion/Command/InitCommand.php" name="src/Onion/Command/InitCommand.php" role="php" md5sum="9ee53174df1f1ba932e0e449c1f912a7"/>
            <file install-as="Onion/ConfigContainer.php" name="src/Onion/ConfigContainer.php" role="php" md5sum="2bf0c59c46a1eb98cf33acefc4097a1c"/>
            <file install-as="Onion/GlobalConfig.php" name="src/Onion/GlobalConfig.php" role="php" md5sum="b2de5f4a4f385dea7e86598d6f3f11d9"/>
            <file install-as="Onion/Onion.php" name="src/Onion/Onion.php" role="php" md5sum="6a4e7d381b3cb0d8d67d3a7e35ac2537"/>
            <file install-as="Onion/PackageConfigReader.php" name="src/Onion/PackageConfigReader.php" role="php" md5sum="22fd847bbffeec43e82563351540441f"/>
            <file install-as="Onion/Packager.php" name="src/Onion/Packager.php" role="php" md5sum="2fffec686af34345d9faef2efc04eb4a"/>
            <file install-as="Onion/PackageXml/PackageXmlWriter.php" name="src/Onion/PackageXml/PackageXmlWriter.php" role="php" md5sum="0913c185e0731fc1f8b2a126d24a39fb"/>
            <file install-as="Onion/SpecUtils.php" name="src/Onion/SpecUtils.php" role="php" md5sum="3acf35cf3fd15ae23eef6dabb0d210bd"/>
            <file install-as="Onion/TestCommand/ParentCommand/SubCommand.php" name="src/Onion/TestCommand/ParentCommand/SubCommand.php" role="php" md5sum="a69e9e6477f7d4d82f30b5769eae073e"/>
            <file install-as="Onion/TestCommand/ParentCommand.php" name="src/Onion/TestCommand/ParentCommand.php" role="php" md5sum="9bec2094c780a962306c2febc669f8b8"/>
            <file install-as="bootstrap.php" name="tests/bootstrap.php" role="test" md5sum="a4dcfe19452fe6d10e9349fdb424b80d"/>
            <file install-as="helpers.php" name="tests/helpers.php" role="test" md5sum="442b22ed35a844926391dc62c49ec706"/>
            <file install-as="Onion/ConfigContainerTest.php" name="tests/Onion/ConfigContainerTest.php" role="test" md5sum="6d12e7f7d407508d04bbf696ab8d988e"/>
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
        
        $filelist = $p->getPhpReleaseFileList();
        ok( $filelist );

        foreach( $filelist as $install ) {
            ok( $install );
        }
    }
}

