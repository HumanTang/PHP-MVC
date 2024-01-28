<?php

namespace Http\Controllers;

use Core\View;

class HomeController
{
    public function index($id = 1)
    {
        // Your logic for the home page (e.g., rendering a view)
        $view = new View(200, \Core\Utility::base_path('Http/controllers/' . "index.php"));
        return $view;
    }

    // Add more methods as needed for different actions on the home page
    // For example:
    // public function about()
    // {
    //     // Logic for the about page
    //     echo "About Us";
    // }
}
