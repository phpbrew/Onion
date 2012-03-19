<?php
namespace Onion;

class Paths 
{

    static function cache_dir()
    {
        return self::work_dir() . DIRECTORY_SEPARATOR . 'cache';
    }

    static function work_dir() 
    {
        return '.onion';
    }
}




