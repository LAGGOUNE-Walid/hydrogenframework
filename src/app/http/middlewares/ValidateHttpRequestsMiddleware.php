<?php 

namespace src\app\http\middlewares;

class ValidateHttpReuqestsMiddleware {
	
	public function before() {
		return $this;
	}

	public function match($baseUrl, $baseRoutes) {
		return (!in_array($baseUrl, $baseRoutes)) ? false : true;
	}

	public function matchHttpMethod($baseUrl, $method, $baseRoutes, $routesMethods) {
		return ($method === $routesMethods[array_search($baseUrl, $baseRoutes)]);
	}

	public function matchParams($baseUrl, $params, $baseRoutes, $routesParams) {
		return (sizeof($routesParams[array_search($baseUrl, $baseRoutes)]) === sizeof($params));
	}
}