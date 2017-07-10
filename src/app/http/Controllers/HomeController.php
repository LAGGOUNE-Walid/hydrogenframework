<?php 

class HomeController {

	public function index(src\app\http\Controllers\Container $container) {
		$container->get("View")->show("home");
	}

}