<?php

namespace Core;

use Core\Response;

class Utility
{
    public const BASEPATH = __DIR__.'/../';
    public static function dd($value)
    {
        echo "<pre>";
        var_dump($value);
        echo "</pre>";

        die();
    }

    public static function urlIs($value)
    {
        return $_SERVER['REQUEST_URI'] === $value;
    }

    public static function abort($code = 404)
    {
        http_response_code($code);

        require base_path("views/{$code}.php");

        die();
    }

    public static function authorize($condition, $status = Response::FORBIDDEN)
    {
        if (!$condition) {
            self::abort($status);
        }

        return true;
    }

    public static function base_path($path)
    {
        return self::BASEPATH . $path;
    }

    public static function doc_root($path)
    {
        return DOC_ROOT . $path;
    }

    public static function view($path, $attributes = [])
    {
        extract($attributes);

        require self::base_path('views/' . $path);
    }

    public static function controller($path, $attributes = [])
    {
        extract($attributes);

        require self::base_path('Http/controllers/' . $path);
    }

    public static function controller_path($path, $attributes = [])
    {
        extract($attributes);

        return self::base_path('Http/controllers/' . $path);
    }

    public static function redirect($path)
    {
        header("location: {$path}");
        exit();
    }

    public static function old($key, $default = '')
    {
        return Session::get('old')[$key] ?? $default;
    }

    public static function root_url($path)
    {
        return $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $path;
    }
}
