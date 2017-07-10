<?php 

namespace src\Interfaces\Router\Request;

interface HttpRequestLimiterInterface {

	/**
	 * function <validateHttpRequest>
	 * the function used to validate the http request and check the session and match the token and set the token and set time limits and update limits
	 */
	public function validateHttpRequest();

	/**
	 * function <startTime>
	 * the function used to get the real time
	 * @return int
	 */
	public function startTime();

	/**
	 * function <endTime>
	 * the function used to get the time for the requests allowed the user
	 * @return int
	 */
	public function endTime();
	
	/**
	 * function <limitsEnd>
	 * the function used to set a time for ubann the user
	 * @return int
	 */
	public function limitsEnd();

	/**
	 * function <httptoken>
	 * the function used to generate a http token 
	 * @return token
	 */
	public function httptoken();

	/**
	 * function <getFromJson>
	 * the function used to get the data stored in limits.json file
	 * @return array
	 */
	public function getFromJson();

	/**
	 * function <writeToJson>
	 * @param httptoken [type of : token] : the token generated
	 * the function used to write in limits.json file the limits data
	 * @return number of bytes that were written to the file
	 */
	public function writeToJson($httptoken);

	/**
	 * function <existsInJson>
	 * @param httptoken [type of : token] : the token generated
	 * the function used to check if the token stored in the session exists in the limits.json
	 * @return boolean
	 */
	public function existsInJson($httptoken);

	/**
	 * function <check>
	 * @param httptoken [type of : token] : the token generated
	 * the function used to check if is limits and if is time
	 * @return bool
	 */
	public function check($httptoken);

	/**
	 * function <httptoken>
	 * @param httptoken	[type of : token] : the token generated
	 * the function used to check if the requests in time is equals to limited requests
	 * @return bool
	 */
	public function isLimits($httptoken);

	/** 
	 *function <isTime>
	 * @param httptoken	[type of : token] : the token generated
	 * the function used to check if the time is between the start time and end time
	 * @return bool
	 */
	public function isTime($httptoken);

	/**
	 * function <updateLimits>
	 * @param httptoken	[type of : token] : the token generated
	 * the function used to add 1 to the user limits in limits.json
	 * @return number of bytes that were written to the file
	 */
	public function updateLimits($httptoken);

	/**
	 * function <putLimitsEnd>
	 * @param httptoken	[type of : token] : the token generated
	 * the function used to add limits end time to limits.json
	 * @return number of bytes that were written to the file
	 */
	public function putLimitsEnd($httptoken);

	/**
	 * function <removeJson>
	 * @param httptoken	[type of : token] : the token generated
	 * the function used to remove a request limtis from limits.json
	 * @return number of bytes that were written to the file
	 */
	public function removeJson($httptoken);

	/**
	 * function <isTimeToRemoveJson>
	 * @param httptoken	[type of : token] : the token generated
	 * the function used to check if the real time is greater than the end time
	 * @return number of bytes that were written to the file
	 */
	public function isTimeToRemoveJson($httptoken);


}