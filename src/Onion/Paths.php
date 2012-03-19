<?php
namespace Onion;

class Paths 
{

    static function home_dir()
    {
        // For Windows ?
        return getenv('HOME') . DIRECTORY_SEPARATOR . '.onion';
    }

    static function system_cache_dir()
    {
        return self::home_dir() . DIRECTORY_SEPARATOR . 'cache';
    }

    static function cache_dir()
    {
        return self::work_dir() . DIRECTORY_SEPARATOR . 'cache';
    }

    static function work_dir() 
    {
        return '.onion';
    }
}




