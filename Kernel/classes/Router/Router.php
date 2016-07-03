<?php

namespace Kernel\classes\Router;
use Kernel\Interfaces\Router\Router as RouterInterface;

class Router implements RouterInterface {
	
	public $htaccessUrl;
	public $urls 			= [];
	public $routeParams 	= [];
	public $htaccessParams 	= [];
	public $controllers 	= [];
	public $methods 		= []; 
	public $httpMethod 		= [];
	
	
	public function load() {
		
		require 'Kernel/Routes/routes.php';
		
	}
	
	public function checkHttpMethod($type) {
		switch ($type) {
			case 'POST':
				if($_SERVER['REQUEST_METHOD']!==$type)
				{
					http_response_code(405);
					echo "method not allowed: GET : In this page the http method must be POST";
					exit();
				}
			break;
			case 'GET':
				if($_SERVER['REQUEST_METHOD']!==$type)
				{
					http_response_code(405);
					echo "method not allowed: POST : In this page the http method must be GET";
					exit();
				}			
			break;
			case "ALL":
				return true;
			break;
			
		}
	}
	
	public function validate($htaccessUrl) {
		
		$htaccessUrl = $this->htaccessUrl;
		$htaccessUrl = htmlspecialchars(trim($htaccessUrl));
		$htaccessUrl = explode("/",$htaccessUrl);
		$baseHtaccessUrl = $htaccessUrl[0];
		unset($htaccessUrl[0]);
		$this->htaccessParams = $htaccessUrl;
		$this->htaccessUrl = $baseHtaccessUrl;
		
	}
	
	public function controller($controllerName,$methodName,array $params) {
		
		if (file_exists("Kernel/controllers/".$controllerName.".php")) {
					
			$path_to_controller = "Kernel/controllers/".$controllerName.".php";
			require $path_to_controller; 
			$ctrlClass = new $controllerName;	
			$functions = get_class_methods($ctrlClass);

			if (in_array($methodName, $functions)) { 

				return $ctrlClass->$methodName($params);
				
			}else{
				echo "{$methodName} function not found in class {$controllerName}";
				exit();
			}

			}else {
				echo "No controller Found for this route";
				exit();
			}


	}
	
	public function add( array $routeUrls ) {
		
		$this->validate($this->htaccessUrl);
		
		foreach($routeUrls as $routeUrl => $options) {
			
			$routeUrl 		= explode("/",$routeUrl);
			$baseRouteUrl 	= $routeUrl[1];
			unset($routeUrl[1]);
			unset($routeUrl[0]);
			$routeParams 	= $routeUrl;

			array_push($this->urls,$baseRouteUrl);
			array_push($this->routeParams,$routeParams);
			
			$options 	= explode("|",$options);
			$controller = $options[0];
			$httpMethod = $options[1];
			$controller = explode(".",$controller);
			$class 		= $controller[0];
			$method 	= $controller[1];
			
			array_push($this->controllers,$class);
			array_push($this->methods,$method);
			array_push($this->httpMethod,$httpMethod);
			
		}
		
		if(in_array($this->htaccessUrl,$this->urls)) {
			
			$place = array_search($this->htaccessUrl,$this->urls);
			$this->checkHttpMethod($this->httpMethod[$place]);
			
			if(sizeof($this->routeParams[$place])===sizeof($this->htaccessParams)) {
				
				$params = array_combine($this->routeParams[$place],$this->htaccessParams);
				$this->controller($this->controllers[$place],$this->methods[$place],$params);
				
			}else {
				die("Parameters doesn't Match .");
			}
			
		}else {
			return require 'views/errors/404.php';
		}
		
	}	
	
}