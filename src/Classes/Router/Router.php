<?php 

namespace src\Classes\Router;

use src\Classes\Loger as Loger;
use src\app\http\Controllers\Container as Container;
use src\Exceptions\HydrogenExceptions as HydrogenExceptions;
use src\Interfaces\Router\RouterInterface as RouterInterface;
use src\app\http\middlewares\ValidateHttpReuqestsMiddleware as ValidateHttpReuqestsMiddleware;

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
	 * @var routeMethods [type of : array] : store the routes http request type
	 */
	public $routesMethods 		= [];

	/**
	 * @var view [type of : object] : contain the View object
	 */
	public $view = NULL;

	/**
	 * @var finalParams [type of : array] : store the route parameters with the values from the request
	 */
	public $finalParams = [];

	/**
	 * @var baseUrl [type of : string] : store the request url
	 */
	public $baseUrl = NULL;

	/**
	 * @var callBackName [type of : array] : contain the name of the function that will be called
	 */
	public $callBackName = [];

	/**
	 * @var ValidateHttpReuqestsMiddleware [type of : object] : store the ValidateHttpReuqestsMiddleware class
	 */
	public $ValidateHttpReuqestsMiddleware = NULL;

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:12
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
	}

	public function add($routeName, $callBack) {
		return $this->routes[$routeName] = [$callBack];
	}

	public function run() {
		return $this->analyseRoutes($this->routes);
	}

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:20
	*/
	public function analyseRoutes($routes) {
		foreach ($routes as $route => $callBack) {
			$route 					= explode(":", $route); 
			$routeMethod 			= $route[1];
			unset($route[1]); 
			$route 				= 	explode("/", $route[0]);
			$baseRoute 			= 	$route[1];
			$this->callBackName[$baseRoute] = $callBack;
			unset($route[0]);
			unset($route[1]);
			$routeParams 		= 	$route; 
			array_push($this->baseRoutes, $baseRoute);
			array_push($this->routesParams, $routeParams);
			array_push($this->routesMethods, $routeMethod);
		}
		return $this->analyseHttpRequest($_GET);
	}

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:29
	*/
	public function analyseHttpRequest($httpReuqest) {

		$httpReuqest = 	explode("/", htmlspecialchars($httpReuqest["url"]));
		$baseUrl	 = 	$httpReuqest[0];
		(new Loger)->log($_SERVER["REQUEST_URI"]);
		$this->ValidateHttpReuqestsMiddleware = new ValidateHttpReuqestsMiddleware;
		$this->baseUrl = $baseUrl;
		unset($httpReuqest[0]);
		$params 	= $httpReuqest;
		$method 	= $_SERVER['REQUEST_METHOD']; 
		try {
			if(!$this->ValidateHttpReuqestsMiddleware->before()->match($baseUrl, $this->baseRoutes)) {
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
			if (!$this->ValidateHttpReuqestsMiddleware->before()->matchHttpMethod($baseUrl, $method, $this->baseRoutes, $this->routesMethods)) {
				http_response_code(405);
				throw new HydrogenExceptions("HTTP method not acceptable,", 4);
			}
		} catch (HydrogenExceptions $e) {
			$ex = get_class($e);
			echo($e->getMessage()." error code : #".$e->getCode()." [$ex]");
			exit();
		}
		try {
			if (!$this->ValidateHttpReuqestsMiddleware->before()->matchParams($baseUrl, $params, $this->baseRoutes, $this->routesParams)) {
				throw new HydrogenExceptions("[HTTP] : 404 not found,", 3);
			}	
		} catch (HydrogenExceptions $e) {
			http_response_code(404);
			$ex = get_class($e);
			$erorr = $e->getMessage()." error code : #".$e->getCode()." [$ex]";
			$this->view->show("errors/404", ["error" => $erorr]);
			exit();
		}
		$this->finalParams = array_combine($this->routesParams[array_search($baseUrl, $this->baseRoutes)], $params);
		return call_user_func($this->callBackName[$baseUrl][0], $this->finalParams, new Container);
	}

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:38
	*/
	public function middleware($type, $middlewareName, $container) {
		if($type === "BEFORE") {
			return $container->get("Middleware")
						->run("BEFORE", $middlewareName, $this->baseUrl, $this->routesMethods[array_search($this->baseUrl, $this->baseRoutes)], $this->finalParams, $container);
		}
		if($type === "AFTER") {	
			return $container->get("Middleware")
						->run("AFTER", $middlewareName, $this->baseUrl, $this->routesMethods[array_search($this->baseUrl, $this->baseRoutes)], $this->finalParams, $container);
		}
	}

	/**
	 * Description: src/Interfaces/Router/RouterInterface.php Line:50
	*/
	public function controller($controllerName, $controllerMethod, $params, $container) {
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
		return $controller->$controllerMethod($params, $container);
	}	
}
