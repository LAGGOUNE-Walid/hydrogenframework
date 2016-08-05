<?php

use Kernel\controllers\BaseController;
use Kernel\Models\User as User;

class HomeController extends BaseController {


		public function index() {

			return $this->View->show("home");

		}

}
