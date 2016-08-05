<?php

/**
	*
	* BaseController created to load the classes and framework configuration
	*
*/

namespace Kernel\Controllers;

#-----------------------------------------------------------#
use Kernel\classes\View\View 								as 	View;   		#
use Kernel\classes\Session\Session  				as 	Session;		#
use Kernel\classes\Database\Database 				as 	Database;		#
use Kernel\classes\Validator\Validate 			as 	Validate;		#
use Kernel\classes\Validator\Factory 				as 	Factory;		#
use Kernel\Models\User\User 								as 	User;				#
use Kernel\classes\Router\HttpCache 				as 	HttpCache;	#
use Kernel\classes\Router\Request\Request 	as 	Request; 		#
#-----------------------------------------------------------#


class BaseController {


		public $Session;
		public $View;
		public $Database;
		public $Validator;
		public $mail;
		public $User;
		public $HttpCache;

		public $MailerConfig;

		public function __construct() {

			$this->Session 				= new Session		();
			$this->View 					= new View			();
			$this->Database 			= new Database	();
			$this->Validator 			= new Factory		();
			$this->User 					= new User			();
			$this->HttpCache 			= new HttpCache ();
			$this->Request 				= new Request		();


			$this->startBootingLibs();
			$this->setMailerConfig();

			require "Kernel/startMailer.php";

		}

		public function startBootingLibs() {

			$files = parse_ini_file("Kernel/config.ini" ,true);
			foreach ($files['Libraries'] as $name => $path) {

				if (file_exists($path)) {

					require $path;

				}else {

					echo "No $name found";
					exit();

				}

			}

		}





		public function setMailerConfig() {

			$mailer = parse_ini_file("Kernel/config.ini" ,true);
			$mailer = $mailer['phpMailer'];
			$this->MailerConfig = $mailer;

		}


}
