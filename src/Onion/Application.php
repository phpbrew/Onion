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
    const version = '1.2.1';

    function brief()
    {
        return 'Onion - PHP Package builder.';
    }

    function options($opts)
    {
        parent::options($opts);
    }

    function init()
    {
        parent::init();
        $this->registerCommand( 'init' );
        $this->registerCommand( 'build' );
        $this->registerCommand( 'compile' );
        $this->registerCommand( 'bundle' );
        $this->registerCommand( 'self-update' );
    }

    function getCache()
    {
        static $cache;
        if( $cache )
            return $cache;

        $cache = new FileSystemCache(array(
            'expiry' => 60 * 30, // 30 minutes
            'cache_dir' => Paths::system_cache_dir(),
        ));
        return $cache;
    }

    static function getInstance()
    {
        static $instance;
        $instance = new self;
        return $instance;
    }

}
