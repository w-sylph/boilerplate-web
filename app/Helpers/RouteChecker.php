<?php

namespace App\Helpers;

use Illuminate\Routing\Route as Route;

use Auth;
use Request;

class RouteChecker
{
	protected $route;

    public function __construct() {
        $this->route = Request::route();
    }

    /**
     * Check if current route is equal to the given route
     *
     * @param  string  $routeName
     * @param  string  $output
     * @return string
     */
    public function isOnRoute($routeName, $output = "active menu-open") {
    	
        if($this->route->getName() == $routeName) {
    		return $output;
        }

        return null;
    }

    /**
     * Check if current route is equal to the given array of route
     *
     * @param  array  $routeNames
     * @param  string  $output
     * @return string
     */
    public function areOnRoutes(array $routeNames, $output = "active menu-open") {

        foreach($routeNames as $routeName) {
            if($this->isOnRoute($routeName, true)) {
                return $output;
            }
        }

        return null;
    }
}