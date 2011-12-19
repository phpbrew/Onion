<?php
namespace Onion\Command;
use CLIFramework\Command;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Phar;

/**
 * Compile package to phar file.
 *
 * phar file structure
 *
 * {{Stub}}
 *    {{ClassLoader}}
 *    {{Bin or Executable or Bootstrap}}
 * {{Halt Compiler}}
 * {{Content Section}}
 */
class CompileCommand extends Command
{
    function options($opts)
    {
        // optional classloader script (use Universal ClassLoader by default 
        $opts->add('classloader?','embed classloader source file');

        // append executable (bootstrap scripts, if it's not defined, it's just a library phar file.
        $opts->add('bootstrap?','bootstrap or executable source file');

        $opts->add('executable','is a executable script ?');

        $opts->add('lib+','external source dir');

        $opts->add('output:','output');

        $opts->add('c|compress?', 'phar file compress type: gz, bz2');

        $opts->add('no-compress', 'do not compress phar file.');
    }

    function brief()
    {
        return 'compile current source into Phar format library file.';
    }

    function execute($arguments)
    {
        $options = $this->getOptions();
            
        $logger = $this->getLogger();

        $bootstrap = null;
        $lib_dirs = array('src'); // current package source, TODO: we should read the roles from package.ini
        $output = 'output.phar';
        $classloader = null;


        if( $options->bootstrap )
            $bootstrap = $options->bootstrap->value;

        if( $options->lib )
            $lib_dirs = $options->lib->value;

        if( $options->output )
            $output = $options->output->value;


        $this->logger->info('Compiling Phar...');

        $pharFile = $output;
        $src_dirs  = $lib_dirs;

        $this->logger->info2("Creating phar file $pharFile...");

        $phar = new Phar($pharFile, 0, $pharFile);
        $phar->setSignatureAlgorithm(Phar::SHA1);
        $phar->startBuffering();


        // archive library directories into phar file.
        foreach( $lib_dirs as $src_dir ) {
            if( ! file_exists($src_dir) )
                die( "$src_dir does not exist." );

            $src_dir = realpath( $src_dir );
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src_dir),
                                    RecursiveIteratorIterator::CHILD_FIRST);

            // compile php file only (currently)
            foreach( $iterator as $path ) {
                if( $path->isFile() ) {
                    if( preg_match('/\.php$/',$path->getFilename() ) ) {
                        $rel_path = substr($path->getPathname(),strlen($src_dir) + 1);
                        $content = php_strip_whitespace( $path->getRealPath() );
                        # echo $path->getPathname() . "\n";
                        $this->logger->debug("\tcompile " . $rel_path );
                        $phar->addFromString($rel_path, $content);
                    }
                }
            }
        }

        // including bootstrap file
        if( $bootstrap ) {
            $this->logger->info2( "Adding bootstrap file $bootstrap..." );
            $content = php_strip_whitespace($bootstrap);
            $content = preg_replace('{^#!/usr/bin/env\s+php\s*}', '', $content);
            $phar->addFromString($bootstrap, $content);
        }

        $stub = '';

        if( $options->executable ) {
            $this->logger->info2( 'Adding shell bang...' );
            $stub .= "#!/usr/bin/env php\n";
        }

        $this->logger->info2( "Setting up stub..." );
        $stub .= <<<"EOT"
<?php
Phar::mapPhar('$pharFile');
EOT;

        // use stream to resolve Universal\ClassLoader\Autoloader;
        if( $options->classloader ) {

            $this->logger->info2( "Adding classloader..." );

            if( is_string( $options->classloader->value ) && file_exists( $options->classloader->value ) )
            {
                $classloader_file = $options->classloader->value;
                $content = php_strip_whitespace($classloader_file);
                $phar->addFromString($classloader_file,$content);
                $stub .=<<<"EOT"
require 'phar://$pharFile/$classloader_file';
EOT;
            }
            else {
                $classloader_file = 'Universal/ClassLoader/SplClassLoader.php';
                $classloader_path = stream_resolve_include_path($classloader_file);

                if( ! $classloader_path ) {
                    $classloader_path = stream_resolve_include_path( 'phar://onion.phar/' . $classloader_file);
                }

                if( ! $classloader_path ) {
                    die($classloader_file . ' not found.');
                }

                // try to resolve in current phar executable
                $content = php_strip_whitespace($classloader_path);
                $phar->addFromString($classloader_file,$content);
                $stub .=<<<"EOT"
require 'phar://$pharFile/$classloader_file';
\$classLoader = new \\Universal\\ClassLoader\\SplClassLoader;
\$classLoader->addFallback( 'phar://$pharFile' );
\$classLoader->register();
EOT;

            }

        }


        if( $bootstrap ) {
        $this->logger->info2( "Adding bootstrap script..." );
        $stub .=<<<"EOT"
require 'phar://$pharFile/$bootstrap';
EOT;
        }

        $stub .=<<<"EOT"
__HALT_COMPILER();
EOT;

        $phar->setStub($stub);
        $phar->stopBuffering();

        $compress_type = Phar::GZ;
        if( $options->{'no-compress'} ) 
        {
            $compress_type = null;

        } 
        elseif( $options->compress ) 
        {
            switch( $v = $options->compress->value ) {
            case 'gz':
                $compress_type = Phar::GZ;
                break;
            case 'bz2':
                $compress_type = Phar::BZ2;
                break;
            default:
                throw new Exception("Compress type: $v is not supported, valids are gz, bz2");
                break;
            }
        }

        if( $compress_type ) {
            $this->logger->info( "Compressing phar ..." );
            $phar->compressFiles($compress_type);
        }

        $this->logger->info('Done');
    }
}
