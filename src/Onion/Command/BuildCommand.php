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
use Onion\Pear\PackageXmlGenerator;

class BuildCommand extends Command 
    implements CommandInterface
{
    function brief()
    {
        return 'build PEAR package.';
    }

    function help()
    {
        return <<< EOT
How To

    Define your package.ini file first.

    run the command below to build PEAR package:
    
        $ onion.phar build

EOT;
    }

    function options($getopt)
    {

    }

    function execute() 
    {
        // options result.
        $options = $this->getOptions();
        $logger = $this->getLogger();

        if( ! file_exists('package.ini' ) )
            die('package.ini does not exist. please create one.');

        $logger->info( 'Checking directory structure...' );
        if( is_dir('src') )
            $logger->info2( '* found src/', 1 );
        else
            $logger->warn( '* src/ directory not found.',1 );

        if( is_dir('tests') )
            $logger->info2( '* found tests/', 1 );
        else
            $logger->warn( '* tests/ directory not found.',1 );

        if( is_dir('doc') )
            $logger->info2( '* found doc/', 1 );
        else
            $logger->warn( '* doc/ directory not found.',1 );


        $logger->info( 'Configuring package.ini' );
        $config = new PackageConfigReader();
        $config->setLogger( $logger );

        $package = $config->read( 'package.ini' );

		$generator = new PackageXmlGenerator( $package );
		$generator->setLogger( $logger );

        $logger->info('Writing package.xml...');
		$xml = $generator->generate();
		file_put_contents( 'package.xml', $xml );

        # $this->logger->info('Validating package...');
        # system('pear -q package-validate');

        $logger->info('Building PEAR package...');
        system('pear -q package');

        $logger->info('Done.');
        return true;
    }
}
