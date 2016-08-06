<?php

namespace Kernel\classes\View;
use Kernel\Interfaces\View\View as ViewInterfaces;

class View implements ViewInterfaces {

	public $data  = [];

	public function __set($key,$value) {

		$this->data[$key] = $value;

	}

	public function __get($key) {

		return $this->data[$key];

	}

	public function show($viewName) {

		if(file_exists("views/{$viewName}.php")) {
			require "views/{$viewName}.php";
			return $this;

		}else {
			echo "No {$viewName} view found";
			exit();
		}

	}

	public function redirect($to) {

		header("Location: ".preg_replace("/index.php/", "{$to}", $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));
		exit();
		
	}

	public function url($url) {

		return preg_replace("/index.php/", "{$url}", $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

	}


}
