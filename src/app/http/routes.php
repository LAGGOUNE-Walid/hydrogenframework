<?php

$this->routes = [
	"/home" => "HomeController.index|GET|BasicMiddleware", 
	"/" => "HomeController.index|GET",
];