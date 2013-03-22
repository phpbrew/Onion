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
namespace Onion;
use Onion\Paths;
use CacheKit\FileSystemCache;

class Application extends \CLIFramework\Application
{
    const name = 'Onion';
    const version = '1.6.0';

    public function brief()
    {
        return 'Onion - PHP Package builder.';
    }

    public function options($opts) 
    {
        parent::options($opts);
    }

    public function init()
    {
        parent::init();
        $this->registerCommand( 'init' );
        $this->registerCommand( 'build' );
        $this->registerCommand( 'compile' );
        $this->registerCommand( 'install' );

        // for backward-compatible (new command "install")
        $this->registerCommand( 'bundle' , 'Onion\Command\InstallCommand' );
        $this->registerCommand( 'self-update' );
    }

    public function getCache()
    {
        static $cache;
        if( $cache )
            return $cache;

        $cache = new FileSystemCache(array(
            'expiry' => 60, // 60 seconds
            'cache_dir' => Paths::system_cache_dir(),
        ));
        return $cache;
    }

    static function getInstance()
    {
        static $instance;
        if($instance)
            return $instance;
        return $instance = new self;
    }

}
