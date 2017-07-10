<?php 

namespace src\Interfaces\View;

interface ViewInterface {

	/**
	 * function <show>
	 * @param viewName [type of : string] : is the view file name.
	 * @param variables	[type of : array] : is the variables you like to pass it to the view .
	 * the function used to load the view name and pass to it the variables
	 * @throws src\Exceptions\HydrogenExceptions 
	 * @return require the view file
	 */
	public function show($viewName, $variables);

	/**
	 * function <url>
	 * @param url [type of : string] : the route name
	 * the function used to generate full link to a given route name
	 * @return string the link
	 */
	public function url($url);

}