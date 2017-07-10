<?php 

namespace src\Classes\Router\Request;

use src\Interfaces\Middleware\MiddlewareInterface as MiddlewareInterface;

class Middleware implements MiddlewareInterface {

	 /**
	   * Description: src/Interfaces/Middleware/MiddlewareInterface.php Line:18
	  */
	public function run($type, $middleware, $baseUrl, $httpMethod, $params, $container) {
		if(!empty($middleware)) {
			if(!file_exists("src/app/http/middlewares/$middleware.php")) { 
				die("Middleware error : src/app/http/middlewares/$middleware.php not found !"); 
			}
			require_once("src/app/http/middlewares/$middleware.php");
			if ($type === "BEFORE") {
				if (!method_exists(new $middleware, "before")) {
					die("Middleware error : method before() not found in $middleware class");
				}
				return (new $middleware())->before($container, $baseUrl, $httpMethod, $params);
			}elseif ($type === "AFTER") {
				if (!method_exists(new $middleware, "after")) {
					die("Middleware error : method after() not found in $middleware class");
				}
				return (new $middleware())->after($container, $baseUrl, $httpMethod, $params);
			}else {
				die("Middleware error : Unknown middleware type !");
			}
		}
	}

}