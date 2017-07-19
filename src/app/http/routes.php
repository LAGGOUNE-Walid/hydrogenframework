<?php
$this->add("/home:GET", function($params, $container) {
	$this->middleware("BEFORE", "BasicMiddleware", $container);
	$this->controller("HomeController", "home", $params, $container);
	$this->middleware("AFTER", "BasicMiddleware", $container);
});
$this->run();