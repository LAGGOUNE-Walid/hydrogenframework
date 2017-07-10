<?php 

namespace src\Classes\Router;

use src\Classes\Loger as Loger;
use src\app\http\Controllers\Container as Container;
use src\Exceptions\HydrogenExceptions as HydrogenExceptions;
use src\Interfaces\Router\RouterInterface as RouterInterface;

class Router implements RouterInterface {

	/**
	 * @var routes [type of : array] : contain all the app routes.
	*/
	public $routes 				= 	[];

	/**
	 * @var baseRoutes [type of : array] : contain all the routes base routes.
	*/
	public $baseRoutes 			= 	[];

	/**
	 * @var routesParams [type of : array] : contain the base routes params.
	*/
	public $routesParams 		= 	[];

	/**
	 * @var routesControllers [type of : array] : contain the base routes controllers
	*/
	public $routesControllers 	= 	[];

	/**
	 * @var routesMethods [type of : array] : contain	the base routes http methods.
	*/
	public $routesMethods 		=	[];

	/**
	 * @var middlewares [type of : array] : store the application midldewares
	 */
	public $middlewares 		= [];

	/**
	* @var controllerMethod [type of : array] : contain the routes controllers methods.
	*/
	public $controllersMethods 	= 	[];

	/**
	 * @var view [type of : object] : contain the View object
	 */
	public $view = NULL;

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:11
	*/
	public function loadRoutes() {
		$this->view = (new Container)->get("View");
		try {
			if (!file_exists("src/app/http/routes.php")) {
				throw new HydrogenExceptions("File: routes.php not found ,", 1);
				exit();
			}
		} catch (HydrogenExceptions $e) {
			$ex = get_class($e);
			echo($e->getMessage()." error code: #".$e->getCode()." [$ex]");
			exit();
		}
		require "src/app/http/routes.php";
		$this->analyseRoutes($this->routes);
		$this->analyseHttpRequest($_GET);
	}

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:19
	*/
	public function analyseRoutes($routes) {
		foreach ($routes as $route => $value) {
			$route 				= 	explode("/",$route);
			$baseRoute 			= 	$route[1];
			unset($route[0]);
			unset($route[1]);
			$routeParams 		= 	$route; 
			$routeOptions 		= 	explode("|", $value);
			$controller 		=	$routeOptions[0];
			$middleware 		= 	(array_key_exists(2, $routeOptions)) ? $routeOptions[2] : NULL;
			$controller 		= 	explode(".",$controller);
			$controllerName 	=  	$controller[0];
			$controllerMethod 	= 	$controller[1];
			$httpMethod 		= 	$routeOptions[1];
			array_push($this->baseRoutes, $baseRoute);
			array_push($this->routesParams, $routeParams);
			array_push($this->routesControllers, $controllerName);
			array_push($this->controllersMethods, $controllerMethod);
			array_push($this->routesMethods, $httpMethod);
			array_push($this->middlewares, $middleware);

		}
	}

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:27
	*/
	public function analyseHttpRequest($httpReuqest) {

		$httpReuqest = 	explode("/", htmlspecialchars($httpReuqest["url"]));
		$baseUrl	 = 	$httpReuqest[0];
		unset($httpReuqest[0]);
		$params 	= $httpReuqest;
		$method 	= $_SERVER['REQUEST_METHOD']; 
		try {
			if(!$this->match($baseUrl)) {
				throw new HydrogenExceptions("[HTTP] : 404 not found,", 2);
			}
		} catch (HydrogenExceptions $e) {
			http_response_code(404);
			$ex = get_class($e);
			$erorr = $e->getMessage()." error code : #".$e->getCode()." [$ex]";
			$this->view->show("errors/404", ["error" => $erorr]);
			exit();
		}

		try {
			if (!$this->matchParams($baseUrl, $params)) {
				throw new HydrogenExceptions("[HTTP] : 404 not found,", 3);
			}	
		} catch (HydrogenExceptions $e) {
			http_response_code(404);
			$ex = get_class($e);
			$erorr = $e->getMessage()." error code : #".$e->getCode()." [$ex]";
			$this->view->show("errors/404", ["error" => $erorr]);
			exit();
		}

		try {
			if (!$this->matchHttpMethod($baseUrl, $method)) {
				http_response_code(405);
				throw new HydrogenExceptions("HTTP method not acceptable,", 4);
			}
		} catch (HydrogenExceptions $e) {
			$ex = get_class($e);
			echo($e->getMessage()." error code : #".$e->getCode()." [$ex]");
			exit();
		}	
		(new Container)->get("Middleware")
						->run("BEFORE", $this->middlewares[array_search($baseUrl, $this->baseRoutes)],$baseUrl, $method, array_combine($this->routesParams[array_search($baseUrl, $this->baseRoutes)], $params),new Container);
		$this->getController($baseUrl,$params);
		(new Container)->get("Middleware")
						->run("AFTER", $this->middlewares[array_search($baseUrl, $this->baseRoutes)],$baseUrl, $method, array_combine($this->routesParams[array_search($baseUrl, $this->baseRoutes)], $params), new Container);
	}

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:35
	*/
	public function match($baseUrl) {
		return (!in_array($baseUrl, $this->baseRoutes)) ? false : true;
	}

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:44
	*/
	public function matchParams($baseUrl, $params) {
		return (!(sizeof($this->routesParams[array_search($baseUrl, $this->baseRoutes)]) === sizeof($params))) ? false : true;
	}

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:54
	*/
	public function matchHttpMethod($baseUrl, $method) {
		return (!($method === $this->routesMethods[array_search($baseUrl, $this->baseRoutes)])) ? false : true;
	}

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:61
	*/
	public function getController($baseUrl, $params) {
		$controllerName 	= 	$this->routesControllers[array_search($baseUrl, $this->baseRoutes)];
		$controllerMethod 	= 	$this->controllersMethods[array_search($baseUrl, $this->baseRoutes)];
		try {
			if (!file_exists("src/app/http/Controllers/".$controllerName.".php")) {
				throw new HydrogenExceptions("$controllerName not found, ", 5);
			}
		} catch (HydrogenExceptions $e) {
			$ex = get_class($e);
			echo($e->getMessage()." error code: #".$e->getCode()." [$ex]");
			exit();
		}
		require "src/app/http/Controllers/".$controllerName.".php";
		$controller = new $controllerName();
		try {
			if (!method_exists($controller, $controllerMethod)) {
				throw new HydrogenExceptions("$controllerMethod not found in $controllerName", 6);
			}
		} catch (HydrogenExceptions $e) {
			$ex = get_class($e);
			echo($e->getMessage()." error code: #".$e->getCode()." [$ex]");
			exit();
		}
		$finalParams = array_combine($this->routesParams[array_search($baseUrl, $this->baseRoutes)], $params);
		(new Loger)->log($baseUrl);
		if (sizeof($finalParams) > 0) {
			$controller->$controllerMethod($finalParams, new Container);
		}else {
			$controller->$controllerMethod(new Container);
		}	
	}	
}