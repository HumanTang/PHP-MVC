<?php

use PHPUnit\Exception;
use PHPUnit\Framework\TestCase;

use Core\Router;


class RouterTest extends TestCase
{

    public function testRouteMatching()
    {
        // Create an instance of the Router
        $router = new Router();

        // Define a route with a dynamic segment :method
        $router->get('/', 'index.php');

        // Test a matching URI
        $matchedController = $router->route('/', 'GET');
        $this->assertEquals(200, $matchedController->status);

        // Test a non-matching URI
        $nonMatchingController = $router->route('/nonexistent', 'GET');
        $this->assertEquals(404, $nonMatchingController->status);
    }

    public function testNewRouteMatching()
    {
        // Create an instance of the Router
        $router = new Router();

        // Define a route with a dynamic segment :method
        $router->get('/:method', 'HomeController@index');

        // Test a matching URI
        $matchedController = $router->new_route('/index', 'GET');
        $this->assertEquals(200, $matchedController->status);

        // Test a non-matching URI
        $nonMatchingController = $router->route('/nonexistent', 'GET');
        $this->assertNull(404, $nonMatchingController->status);
    }

    public function testUriPatternMatching()
    {
        $router = new Router();

        // Define routes with dynamic segments
        $router->get('/user/:id', 'HomeController@index');
        $router->get('/home/:method', 'HomeController@index');

        // Test a matching URI
        $isMatchUser = $router->urlPatternMatch('/user/123');
        $this->assertTrue($isMatchUser);

        $isMatchHome = $router->urlPatternMatch('/home/index');
        $this->assertTrue($isMatchHome);
    }

    public function testCallClassMethod(){
        $controllerClassName = "Http\Controllers\HomeController";
        $methodName = "index";
        $controllerInstance = new $controllerClassName();
        $code = 200;
        $success = false;
        $msg = "";
        try{
            $msg = call_user_func_array([$controllerInstance, $methodName], []);
            $success = true;
        }catch(\Exception $exception){
            error_log("Exception caught: " . $exception->getMessage());
            error_log("Stack trace: " . $exception->getTraceAsString());

            // Rethrow the exception to propagate it further (if needed)
            $success = false;
            throw $exception;
        }
        $this->assertTrue($success);
        $this->assertEquals("hello", $msg);
    }



}
