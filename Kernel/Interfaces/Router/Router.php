<?php

namespace Kernel\Interfaces\Router;

interface Router {
	public function load();
	public function checkHttpMethod($type);
	public function controller($controllerNmae,$methodName,array $params);
	public function validate($htaccessUrl);
	public function add(array $routeUrls);	
}