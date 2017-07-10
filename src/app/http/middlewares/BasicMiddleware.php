<?php 

class BasicMiddleware {

	public function before($container, $baseUrl, $httpMethod, $params) {
		// do somthing before the application
	}

	public function after($container, $baseUrl, $httpMethod, $params) {
		// do somthing after the application
	}

}