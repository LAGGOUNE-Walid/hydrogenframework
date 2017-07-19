<?php 

class HttpRequestLimiterMiddleware {

	/**
	 * @var time [type of : string] : requests allowed in this time
	 */
	private $limits = 2;

	/**
	 * @var time [type of : string] : requests allowed in this time
	 */
	private $time = "+60 minutes";

	/**
	 * @var limitsEnd [type of : string] : the user banned time
	 */
	public $limitsEnd 	= 	"+5 minutes";

	public function before($container, $baseUrl, $httpMethod, $params) {
		$httpRequestLimiter 			= 	$container->get("HttpRequestLimiter"); 
		$httpRequestLimiter->limits 	= 	$this->limits;  
		$httpRequestLimiter->time 		= 	$this->time; 
		$httpRequestLimiter->limitsEnd 	= 	$this->limitsEnd; 
		return $httpRequestLimiter->validateHttpRequest();
	}

}