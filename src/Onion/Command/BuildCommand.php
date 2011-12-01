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
namespace Onion\Command;
use CLIFramework\Command;
use CLIFramework\CommandInterface;
use Onion\GlobalConfig;
use Onion\PackageConfigReader;

class BuildCommand extends Command 
    implements CommandInterface
{
    function options($getopt)
    {
        $getopt->add('v|verbose','verbose message');
        $getopt->add('d|debug','debug message');
    }

    function execute($cx) 
    {
        // options result.
        $options = $this->getOptions($cx);

        if( ! file_exists('package.ini' ) )
            die('package.ini does not exist. please create one.');

        $cx->logger->info2( 'Checking directory structure...' );
        if( is_dir('src') )
            $cx->logger->info( '* found src/', 1 );
        else
            $cx->logger->warn( '* src/ directory not found.',1 );

        if( is_dir('tests') )
            $cx->logger->info( '* found tests/', 1 );
        else
            $cx->logger->warn( '* tests/ directory not found.',1 );

        if( is_dir('doc') )
            $cx->logger->info( '* found doc/', 1 );
        else
            $cx->logger->warn( '* doc/ directory not found.',1 );

        $cx->logger->info2( 'Configuring package.ini' );
        $config = new PackageConfigReader($cx);
        $config->readAsPackageXml();
        $xml = $config->generatePackageXml();

        /*
        if( file_exists('package.xml') )
            rename('package.xml','package.xml.old');
        */
        file_put_contents('package.xml',$xml);

        # $this->logger->info('Validating package...');
        # system('pear -q package-validate');

        $cx->logger->info2('Building PEAR package...');
        system('pear -q package');

        $cx->logger->info2('Done.');
        return true;
    }
}

