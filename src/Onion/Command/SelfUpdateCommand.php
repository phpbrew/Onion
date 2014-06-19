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

    public function execute($branch = 'master')
    {
        global $argv;
        $script = preg_replace('/ /', '\ ', realpath( $argv[0] ));
        if( ! is_writable($script) ) {
            throw new Exception("$script is not writable.");
        }

        $branch = $this->options->branch ?: $branch ?: 'master';

        // fetch new version phpbrew
        $this->logger->info("self updating ($branch)...");

        $url = "https://github.com/phpbrew/Onion/raw/$branch/onion";
        system("curl -# -L $url > $script") == 0 or die('Update failed.');

        $this->logger->info("Version updated.");

        system( $script . ' --version' );
    }
}




