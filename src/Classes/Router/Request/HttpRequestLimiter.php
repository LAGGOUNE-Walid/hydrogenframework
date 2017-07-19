<?php 

namespace src\Classes\Router\Request;

use src\Exceptions\HydrogenExceptions as HydrogenExceptions;
use src\Interfaces\Router\Request\HttpRequestLimiterInterface as HttpRequestLimiterInterface;

class HttpRequestLimiter implements HttpRequestLimiterInterface {

	/**
	 * @var session [type of : object] : store the session class
	 */
	public $session 	= 	NULL;

	/**
	 * @var limits [type of : int] : the user request time allowed
	 */
	public $limits 	= 	NULL;

	/**
	 * @var time [type of : string] : requests allowed in this time
	 */
	public $time 	= 	NULL;

	/**
	 * @var limitsEnd [type of : string] : the user banned time
	 */
	public $limitsEnd 	= 	NULL;
	
	/**
	 * @var view [type of : object] : store the view class
	 */
	public $view = NULL;

	public function __construct($session, $view) {
		$this->session = $session;
		$this->view 	= $view;
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 11
	 */
	public function validateHttpRequest() {
		start :
		if ($this->session->has("httptoken")) {
			try {
				if(!$this->existsInJson($this->session->get("httptoken"))) {
					$this->session->destroy("httptoken");
					throw new HydrogenExceptions("[HTTP] : httptoken not match our token ! please refresh the page ", 10);
				}
			}catch(HydrogenExceptions $e) {
				$ex = get_class($e);
				$erorr = $e->getMessage()." error code : #".$e->getCode()." [$ex]";
				$this->view->show("errors/http", ["error" => $erorr]);
				exit();
			}	
			if ($this->isTimeToRemoveJson($this->session->get("httptoken"))) {
				$this->removeJson($this->session->get("httptoken"));
				$this->session->destroy();
				goto start;
			}
			if(!$this->check($this->session->get("httptoken"))) {
				try {
					if ($this->getFromJson()[$this->session->get("httptoken")]["limitsEnd"] === 0) {
						$this->putLimitsEnd($this->session->get("httptoken"));
						throw new HydrogenExceptions("Max http requests is ".$this->limits." ! please go back in ".$this->limitsEnd.".", 11);
					}
				}catch(HydrogenExceptions $e) {
					$ex = get_class($e);
					$erorr = $e->getMessage()." error code : #".$e->getCode()." [$ex]";
					$this->view->show("errors/http", ["error" => $erorr]);
					exit();
				}
				try {
					if ($this->startTime() >= $this->getFromJson()[$this->session->get("httptoken")]["limitsEnd"]) {
						$this->removeJson($this->session->get("httptoken"));
						$this->session->destroy("httptoken");
						goto start;
					}else {
						throw new HydrogenExceptions("Max http requests is ".$this->limits." ! please go back in ".$this->limitsEnd.".", 11);
					}
				}catch(HydrogenExceptions $e) {
					$ex = get_class($e);
					$erorr = $e->getMessage()." error code : #".$e->getCode()." [$ex]";
					$this->view->show("errors/http", ["error" => $erorr]);
					exit();
				}
			}
			if ($this->isTimeToRemoveJson($this->session->get("httptoken"))) {
				$this->removeJson($this->session->get("httptoken"));
			}
			$this->updateLimits($this->session->get("httptoken"));
		}else {
			$httptoken = $this->httptoken();
			$this->session->set("httptoken",$httptoken);
			$this->writeToJson($httptoken);
		}
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 18
	 */
	public function startTime() {
		return date("h:i");
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 25
	 */
	public function endTime() {
		return date("h:i",strtotime($this->time,time()));
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 32
	 */ 
	public function limitsEnd() {
		return date("h:i",strtotime($this->limitsEnd,time()));
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 39
	 */
	public function httptoken() {
		return bin2hex(mhash(MHASH_TIGER160, rand()));
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 46
	 */
	public function getFromJson() {
		return json_decode(file_get_contents("src/app/http/request/limits.json"),true);
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 57
	 */
	public function writeToJson($httptoken) {
		$data = $this->getFromJson();
		$data[$httptoken] = ["limits" => 1, "start" => $this->startTime(), "end" => $this->endTime(), "limitsEnd" =>0];
		return file_put_contents("src/app/http/request/limits.json", json_encode($data));
	} 

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 62
	 */
	public function existsInJson($httptoken) {
		$data = $this->getFromJson();
		$c = (array_key_exists($httptoken, $data)) ? true : false;
		return $c;
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 70
	 */
	public function check($httptoken) {
		$isLimits	 = 	$this->isLimits($httptoken);
		$isTime 	= 	$this->isTime($httptoken);
		$c 			=  	($isLimits AND $isTime === true) ? true : false;
		return $c;
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 78
	 */
	public function isLimits($httptoken) {
		$data 	= $this->getFromJson();
		$c 		= ($data[$httptoken]["limits"] >= $this->limits) ? false : true;
		return $c;
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 86
	 */
	public function isTime($httptoken) {
		$data 	= 	$this->getFromJson();
		$c 		= 	($this->startTime() >= $data[$httptoken]["start"] AND $this->startTime() <= $data[$httptoken]["end"]) ? true : false;
		return $c;
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 94
	 */
	public function updateLimits($httptoken) {
		$data = $this->getFromJson();
		$data[$httptoken]["limits"] = $data[$httptoken]["limits"] + 1;
		return  file_put_contents("src/app/http/request/limits.json", json_encode($data));
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 102
	 */
	public function putLimitsEnd($httptoken) {
		$data = $this->getFromJson();
		$data[$httptoken]["limitsEnd"] = $this->limitsEnd();
		return file_put_contents("src/app/http/request/limits.json", json_encode($data));
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 110
	 */
	public function removeJson($httptoken) {
		$data = $this->getFromJson();
		unset($data[$httptoken]);
		return file_put_contents("src/app/http/request/limits.json", json_encode($data));
	}

	/**
	 * Description : src/Interfaces/Router/Request/HttpRequestLimiterInterface.php Line : 118
	 */
	public function isTimeToRemoveJson($httptoken) {
		$data 	= $this->getFromJson();
		$c 		= ($this->startTime() >= $data[$httptoken]["end"]) ? true : false;
		return $c;
	}

}