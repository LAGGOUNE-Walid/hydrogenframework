<?php 

namespace src\Interfaces\Validator;

interface ValidatorInterface {

	/**
	 * function <compile>
	 * @param request [type of : object] : src\Classes\Router\Request\Request::class
	 * @param rules [type of : array] : the validation rules
	 * the function used to compile the rules into a booleans and store the errors in the @var errors
	 */
	public function compile($request, $rules);
	
	/**
	 * function <makeItRequired>
	 * @param request [type of : object] : src\Classes\Router\Request\Request::class
	 * @param inputName [tyoe of : string] : the input name
	 * the function used to check if the input is not empty
	 * @return error or true
	 */
	public function makeItRequired($request, $inputName);

	/**
	 * function <max> 
	 * @param request [type of : object] : src\Classes\Router\Request\Request::class
	 * @param inputName [type of : string] : the input name
	 * @param value [type of : int] : the rule value
	 * the function used to check the length if the input value
	 * @return error or true
	 */
	public function max($request, $inputName, $value);

	/**
	 * function <min>
	 * @param inputName [type of : string] : the input name
	 * @param request [type of : object] : src\Classes\Router\Request\Request::class
	 * @param value [type of : int] : the rule value
	 * the function used to check the length of the input value
	 * @return error or true
	 */
	public function min($request, $inputName, $value);

	/**
	 * fucntion <cleanHtml>
	 * @param request [type of : object] : src\Classes\Router\Request\Request::class
	 * @param inputName [type of : string] : the input name
	 * the function used the strip tags function to delete html codes
	 * @return string
	 */
	public function cleanHtml($request, $inputName);

	/**
	 * function <typeof>
	 * @param type [type of : string] : the rule value
	 * @param inputName [type of : string] : the input name
	 * @param inputValue [type of : text] : the input value
	 * the function used to check the type of the input value with the type given
	 * @return error or true
	 */
	public function typeof($type, $inputName, $inputValue);
}