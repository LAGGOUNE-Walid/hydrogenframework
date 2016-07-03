<?php

namespace Kernel\Interfaces\Session;

interface Session {
	
	public function token();
	public function verifyToken();
	public function set($key,$value);
	public function has($key);
	public function destroy($key);
	public function getAll();
	public function get($key);
	
	
}