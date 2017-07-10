<?php 

namespace src\Classes\View;

use src\Exceptions\HydrogenExceptions as HydrogenExceptions;
use src\Interfaces\View\ViewInterface as ViewInterface;

class View implements ViewInterface {

	/**
	 * @var viewsDir [type of : string] : is the main views dir.
	 */
	public $viewsDir = "src/app/views";

	/**
	 * Description: src/Interfaces/View/ViewInterface.php Line:15
	*/
	public function show($viewName, $variables = []) {
		try {
			if (!file_exists($this->viewsDir."/".$viewName.".php")) {
				throw new HydrogenExceptions("[View] : view $viewName not found ,", 7);
			}
		} catch (HydrogenExceptions $e) {
			$ex = get_class($e);
			echo($e->getMessage()." error code: #".$e->getCode()." [$ex]");
			exit();
		}

		foreach ($variables as $key => $value) {
			${$key} = $value;
		}
		require $this->viewsDir."/".$viewName.".php";
	}

	/**
	 * Description: src/Interfaces/View/ViewInterface.php Line:23
	*/
	public function url($url) {
		return preg_replace("/index.php/", "{$url}", $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
	}

}