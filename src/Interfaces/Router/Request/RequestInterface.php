<?php 

namespace src\Interfaces\Router\Request;

interface RequestInterface {

	/**
	 * function <get>
	 * @param inputName [type of : string] : is the input name .
	 * the function used to get the input data form $_POST
	 * @return string 
	 */
	public function get($inputName);

	/**
	 * function <info>
	 * the function used to get informations about the http request
	 * @return array
	 */
	public function info();

	/**
	 * function <getHeader>
	 * @param name [type of : string]: the header name
	 * @return string or null
	 */
	public function getHeader($name);

	/**
	 * function <putHeader>
	 * @param key [type of : string] : the header key
	 * @param value [type of : string] : the header value
	 * @return boolean
	 */
	public function putHeader($key, $value);

	/**
	 * function <file>
	 * @param fileName [type of : string]: the file input name
	 * function used to select the file data
	 * @return this or null
	 */
	public function file($fileName);

	/**
	 * function <getName>
	 * the function used to get the file name
	 * @return string
	 */
	public function getName();

	/**
	 * function <getExtension>
	 * the function used to get the extension of the file
	 * @return string
	 */
	public function getExtension();

	/**
	 * function <getMimetype>
	 * the function used to get file type
	 * @return string
	 */
	public function getMimetype();

	/**
	 * function <getSize>
	 * the function used to get file file size in MB
	 * @return int
	 */
	public function getSize();

	/**
	 * function <size>
	 * used to get file file size in Bytes
	 * @return int
	 */
	public function size();

	/**
	 * function <getTmp>
	 * the function used to get the tmp path of the file
	 * @return string
	 */
	public function getTmp();

}