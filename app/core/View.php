<?php

namespace app\core;

class View
{
    public $path;
    public $route;
    public $layout = 'default';

    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route['controller'] . '/' . $route['action'];
    }

    public function render($title, $vars = [])
    {
        if (count($vars) >0) extract($vars);
        if (file_exists('app/views/' . $this->path . '.php'))
        {
            ob_start();
            require 'app/views/' . $this->path . '.php';
            $content = ob_get_clean();
            require 'app/views/layouts/' . $this->layout . '.php';
        } else {
            echo 'Вид не найден: ' . $this->path;
            View::errorCode(404);
        }
    }

    public function redirect($url)
    {
        header('location: ' . $url);
        exit();
    }

    public static function errorCode($code)
    {
        http_response_code($code);
        $path = 'app/views/errors/' . $code . '.php';
        if (file_exists($path)) {
            require $path;
        }
        exit();
    }
}