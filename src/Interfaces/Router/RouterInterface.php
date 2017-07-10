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
	 * function <match>
	 * @param method [type of : baseUrl] : the url name
	 * the function used to compare the url given with routes given
	 * @return bollean
	 */
	public function match($baseUrl);

	/**
	 * function <matchParams>
	 * @param baseUrl [type of : string] : the url 
	 * @param baseUrl [type of : params] : the url params
	 * the function used to compare the route params with the url params
	 * @return bollean
	 */
	public function matchParams($baseUrl, $params);


	/**
	 * function <checkHttpMethod>
	 * @param method [type of : string] : the http method should be
	 * @param baseUrl [type of : strong] : the url name
	 * the function used to compare the method given with the http request method
	 * @return bollean
	 */
	public function matchHttpMethod($baseUrl, $method);

	/**
	 * function <getController>
	 * the function used to load the controller
	 * @throws src\Exceptions\HydrogenExceptions 
	 * @return true of controller found or @return false if controller not found
	 */
	public function getController($baseUrl, $params);

}