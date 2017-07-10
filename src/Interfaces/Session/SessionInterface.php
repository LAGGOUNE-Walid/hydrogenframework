<?php

namespace src\Interfaces\Session;

interface SessionInterface {
	
	/**
	 * function <token>
	 * the function used to generate encrypted token
	 * @return string token
	 */
	public function token();

	/**
	 * function <verifyToken>
	 * the function used to compare the http method post token and the token stored in the session
	 * @throws src\Exceptions\HydrogenExceptions
	 */
	public function verifyToken();

	/**
	 * function <set>
	 * @param key [type of : string ] : is the session key
	 * @param value [type of : string] : is the session value
	 * the function used to store data in the session [key => value]
	 * @return boolean
	 */
	public function set($key, $value);

	/**
	 * function <has>
	 * @param key [type of : string] : the session key
	 * the function used to check if the key exists in the session
	 * @return boolean
	 */
	public function has($key);

	/**
	 * function <destroy>
	 * @param key [type of : string] : is the session key
	 * the function used to destroy a session based on the session key or destroy all the sessions
	 * @return boolean 
	 */
	public function destroy($key);

	/**
	 * function <getAll>
	 * the function used to get all the sessions
	 * @return array
	 */
	public function getAll();

	/**
	 * function <get>
	 * @param key [type of : string] : is the session key
	 * @return the session value
	 */
	public function get($key);
		
}