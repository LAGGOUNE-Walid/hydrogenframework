<?php

namespace Kernel\Interfaces\View;

interface view {

	public function show($viewName);
	public function redirect($to);

}
