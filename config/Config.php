<?php

class Config
{
    private static array $config = [];

    public static function getConfig()
    {
        if (!self::$config) {
            $_SERVER['PHP_ENV'] ??= 'default';
            self::$config = include ("{$_SERVER['PHP_ENV']}.php");
        }

        return self::$config;
    }
}