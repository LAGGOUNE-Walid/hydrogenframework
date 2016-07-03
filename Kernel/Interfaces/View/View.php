<?php

namespace Kernel\Interfaces\View;

interface View {

	public function show($viewName);
	public function redirect($to);
	public function url($url);

}
