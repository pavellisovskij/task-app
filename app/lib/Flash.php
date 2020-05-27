<?php

namespace app\lib;

class Flash
{
    protected static $message = '';

    public static function set($key, $value)
    {
        $_SESSION['flash'][$key] = $value;
    }

    public static function get($key)
    {
        self::$message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return self::$message;
    }

    public static function is_set($key)
    {
        return isset($_SESSION['flash'][$key]);
    }
}