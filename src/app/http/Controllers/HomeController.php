<?php 

class HomeController {

	public function home($params, src\app\http\Controllers\Container $container) {
		$container->get("View")->show("home");
	}

}