<?php 

class BasicMiddleware {

	public function before($container, $baseUrl, $httpMethod, $params) {
		// do something before the application
	}

	public function after($container, $baseUrl, $httpMethod, $params) {
		// do something after the application
	}

}