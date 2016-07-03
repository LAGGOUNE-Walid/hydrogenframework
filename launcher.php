<?php

require 'vendor/autoload.php';
use Kernel\classes\Router\Router as Router;
$router = new Router;
$router->htaccessUrl = $_GET['url'];
$router->load();
