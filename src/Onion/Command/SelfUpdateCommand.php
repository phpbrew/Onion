<?php
namespace Onion\Command;
use Exception;
use CLIFramework\Command;

class SelfUpdateCommand extends Command
{
    public function usage() { return 'onion self-update'; }

    public function brief() { return 'self-update'; }

    public function options($opts) {
        $opts->add('b|branch:','master, develop branch');
    }

    public function execute()
    {
        global $argv;
        $script = realpath( $argv[0] );
        if( ! is_writable($script) ) {
            throw new Exception("$script is not writable.");
        }

        $branch = $this->options->branch ?: 'master';

        // fetch new version phpbrew
        $this->logger->info("self updating ($branch)...");

        $url = "https://raw.github.com/c9s/Onion/$branch/onion";
        system("curl -# -L $url > $script") == 0 or die('Update failed.');

        $this->logger->info("Version updated.");

        system( $script . ' --version' );
    }
}




