<?php

namespace src\Interfaces\Router;

interface RouterInterface {

	/**
	 * function <loadRoutes>
	 * Load all routes from src/app/http/routes.php
	 * @throws src\Exceptions\HydrogenExceptions 
	 */
	public function loadRoutes();

	/**
	 * function <analyseRoutes>
	 * @param routes [type of array] : the full routes informations .
	 * the function used to analyse each route
	 * @return bollean
	 */
	public function analyseRoutes($routes);

	/**
	 * function <analyseHttpRequest>
	 * @param httpReuqest [type of : array]
	 * the function used to analyse the http request and check if exists in routes array
	 * @throws src\Exceptions\HydrogenExceptions 
	 * @return false if the route no found
	 */
	public function analyseHttpRequest($httpReuqest);

	/**
	 * function <middleware>
	 * @param type [type of : string] : the middleware type (Before or after the request)
	 * @param middlewareName [type of : string] : the middleware Class name
	 * @param container [type of : object] : the Container class
	 * @return false or void 
	 */
	public function middleware($type, $middlewareName, $container);

	/**
	 * function <controller>
	 * the function used to load the controller
	 * @param controllerName [type of : string] : the controller Class name
	 * @param controllerMethod [type of : string] : the controller method 
	 * @param params [type of : array] : the http request parameters 
	 * @param container [type of : object] : the Container class
	 * @throws src\Exceptions\HydrogenExceptions 
	 * @return void of controller found or @return false if controller not found
	 */
	public function controller($controllerName, $controllerMethod, $params, $container);

}