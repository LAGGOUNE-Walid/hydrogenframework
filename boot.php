<?php 

if (!file_exists("vendor/autoload.php")) {
	die("autoload.php not found try to use : composer dump -o");
}

require "vendor/autoload.php";

use src\Classes\Router\Router as Router;

$router = new Router;
$router->loadRoutes();