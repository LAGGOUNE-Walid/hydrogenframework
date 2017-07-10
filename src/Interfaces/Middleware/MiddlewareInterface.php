<?php 

namespace src\Interfaces\Middleware;

interface MiddlewareInterface {

	/**
	 * function <run>
	 * @param type [type of : string] : the middleware type 
	 * 		1) BEFORE :  before the request handled by the application layer
	 * 		2) AFTER : after the request ...
	 * @param middleware [type of : string] : the middleware class name and filename
	 * @param httpMethod [type of : string] : the http request method type
	 * @param params [type of : array] : the route and the http request parameters
	 * @param container [type of : object] : container the src\app\http\Controllers\Container::class
	 * @return void
	 */
	public function run($type, $middleware, $baseUrl, $httpMethod, $params, $container);

}