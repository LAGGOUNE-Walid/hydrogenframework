<?php 

/**
 * Dependency Injection Containers
 * Container class used to inject every single class in to the controller
 */

namespace src\app\http\Controllers;

use src\Classes\View\View as View;
use src\Classes\Session\Session as Session;
use src\Classes\Router\HttpCache as HttpCache;
use src\Classes\Router\Request\Request as Request;
use src\Classes\Router\Request\Middleware as Middleware;
use src\Exceptions\HydrogenExceptions as HydrogenExceptions;
use src\Classes\Validator\ValidatorFactory as ValidatorFactory;
use src\Classes\Router\Request\HttpRequestLimiter as HttpRequestLimiter;

class Container {

	private $objects = [];
	
	public function __construct() {
		$this->set("View", new View);
		$this->set("Session", new Session);
		$this->set("Request", new Request);
		$this->set("HttpCache", new HttpCache);
		$this->set("Middleware", new Middleware);
		$this->set("ValidatorFactory", new ValidatorFactory);
		$this->set("HttpRequestLimiter", new HttpRequestLimiter($this->get("Session"), $this->get("View")));
	}

	public function set($name, $object) {
		return $this->objects[$name] = $object;
	}

	public function get($name) {
		return $this->objects[$name];
	}

}