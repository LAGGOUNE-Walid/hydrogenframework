<?php 


namespace src\Classes;

class Loger {

	public function log($routeName) {
		if (!file_exists("src/app/http/logs.txt")) {
			die("src/app/http/logs.txt not found ! create it please .");
		}
		$data = file_get_contents("src/app/http/logs.txt");
		$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "Visited: ".$routeName.PHP_EOL.
            "-------------------------".PHP_EOL;
		file_put_contents("src/app/http/logs.txt",$log.$data);
		
	}

}