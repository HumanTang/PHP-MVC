<?php

use Core\Session;
use Core\ValidationException;

const BASE_PATH = __DIR__.'/../';
const DOC_ROOT = __DIR__;
session_start();

require BASE_PATH . 'vendor/autoload.php';
require BASE_PATH . 'Core/functions.php';
require BASE_PATH . 'bootstrap.php';

$router = new \Core\Router();
require BASE_PATH . 'routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

try {
    handleUri($uri, $method, $router);
} catch (ValidationException $exception) {
    Session::flash('errors', $exception->errors);
    Session::flash('old', $exception->old);

    return redirect($router->previousUrl());
}

Session::unflash();

function handleUri($uri, $method, $router)
{
    // Extract controller, action, and id from the URI
    $uriSegments = explode('/', trim($uri, '/'));

    // Check if the URL can be split by '/'
    if (count($uriSegments) >= 2) {
        $controller = $uriSegments[0];
        $action = $uriSegments[1];
        $id = $uriSegments[2] ?? null;
        $routing_url = "/$controller/$action";
        if($id !== null){
            $routing_url = "/$controller/$action/$id";
        }
        // Modify the route based on the extracted controller, action, and id
        $response = $router->new_route($routing_url, $method);
        //require $response->path;
    } else {
        // Fallback to the logic from index_old.php
        $response = $router->route($uri, $method);
        require $response->path;
    }
}