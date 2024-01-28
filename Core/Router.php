<?php

namespace Core;

use Core\Middleware\Authenticated;
use Core\Middleware\Guest;
use Core\Middleware\Middleware;
use Http\Controllers;

class Router
{
    protected $routes = [];

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null
        ];

        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        return $this->add('POST', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        return $this->add('PUT', $uri, $controller);
    }

    public function only($key)
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;

        return $this;
    }

    public function urlPatternMatchWithDelimiter($url) {
        foreach ($this->routes as $route) {
            $pattern = preg_replace_callback('/:([^\/]+)/', function ($matches) {
                return '([^\/]+)';
            }, preg_quote($route['uri'], '#'));

            if (preg_match("#^{$pattern}$#i", $url)) {
                return true;  // Return true if a match is found
            }
        }

        return false;  // Return false after checking all routes
    }

    public function urlPatternMatch($url) {
        foreach ($this->routes as $route) {
            $pattern = preg_replace_callback('/:([^\/]+)/', function ($matches) {
                return '([^\/]+)';
            }, $route['uri']);

            if (preg_match("#^{$pattern}$#i", $url)) {
                return true;  // Return true if a match is found
            }
        }

        return false;  // Return false after checking all routes
    }

    public function urlPatternIsMatch($url,$route) {

            $pattern = preg_replace_callback('/:([^\/]+)/', function ($matches) {
                return '([^\/]+)';
            }, $route['uri']);

            if (preg_match("#^{$pattern}$#i", $url)) {
                return true;  // Return true if a match is found
            }

        return false;  // Return false after checking all routes
    }

    public function new_route($uri, $method)
    {
        $view = null;
        $code = 404;

        // Check if there are wildcards in the URI
//        if (strpos($uri, ':') === false) {
//            // No wildcards, use the route() function approach
//            return $this->route($uri, $method);
//        }

        foreach ($this->routes as $route) {
            $isMatch = false;
            $pattern = preg_replace_callback('/:([^\/]+)/', function ($matches) {
                return '([^\/]+)';
            }, $route['uri']);

            if (preg_match("#^{$pattern}$#i", $uri)) {
                $isMatch = true;  // Return true if a match is found
            }

            if ($isMatch && $route['method'] === strtoupper($method)) {
                preg_match_all('/:([^\/]+)/', $route['uri'], $matches);
                $wildcardNames = $matches[1];
                $wildcardValues = array_slice(explode('/', $uri), -count($wildcardNames));

                $wildcards = array_combine($wildcardNames, $wildcardValues);

                Middleware::resolve($route['middleware']);

                // Use the controller class name from the route definition
                $controllerClassName = $this->getControllerClassName($route['controller']);

                // Check if the controller has the method
                $methodName = $this->getMethodName($wildcards['method'] ?? ''); // Use the correct key

                if (method_exists($controllerClassName, $methodName)) {
                    unset($wildcards['method']);
                    // Call the method dynamically
                    $controllerInstance = new $controllerClassName();
                    $code = 200;
                    try{
                        return call_user_func_array([$controllerInstance, $methodName], $wildcards);
                    }catch(\Exception $exception){
                        error_log("Exception caught: " . $exception->getMessage());
                        error_log("Stack trace: " . $exception->getTraceAsString());

                        // Rethrow the exception to propagate it further (if needed)
                        throw $exception;
                    }

                } else {
                    // Handle method not found
                    return $view = new \Core\View($code, \Core\Utility::base_path("views/{$code}.php"));
                }
            }
        }
    }

    // Helper method to get the full controller class name
    protected function getControllerClassName($controller)
    {
        return 'Http\Controllers\\' . $controller;
    }

// Helper method to get the method name based on the provided action
    protected function getMethodName($action)
    {
        return lcfirst($action);
    }



    public function route($uri, $method)
    {
        $view = null;
        $code = 404;
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                Middleware::resolve($route['middleware']);
                $code = 200;
                $view = new \Core\View($code, \Core\Utility::base_path('Http/controllers/' . $route['controller']));
                return $view;
                //return require \Core\Utility::base_path('Http/controllers/' . $route['controller']);
            }
        }

        return $view = new \Core\View($code, \Core\Utility::base_path("views/{$code}.php"));
        //$this->abort();
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    protected function abort($code = 404)
    {
        http_response_code($code);

        require \Core\Utility::base_path("views/{$code}.php");

        die();
    }
}
