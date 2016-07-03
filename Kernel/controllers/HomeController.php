<?php

use Kernel\controllers\BaseController;

class HomeController extends BaseController {


	public function index() {

		$this->View->show("home");

	}

}
