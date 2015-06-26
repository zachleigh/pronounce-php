<?php

namespace PronouncePHP\Config;

class Config
{
    /**
     * Get config key value
     *
     * @param string $key
     * @return mixed
    */
    public static function get($key)
    {
        $config = include('config.php');

        return $config[$key];
    }
}