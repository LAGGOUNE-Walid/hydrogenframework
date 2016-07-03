<?php

namespace Kernel\Controllers;
use Kernel\classes\View\View as View;
use Kernel\classes\Session\Session  as Session;
use Kernel\classes\Database\Database as Database;

class BaseController {
	
	
	public $Session;
	public $View;
	public $Database;
	
	public function __construct() {
		$this->Session 	= new Session();
		$this->View 	= new View(); 
		$this->Database = new Database();
	}
	
	
}