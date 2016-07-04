<?php

namespace Kernel\Interfaces\Database;

interface Database {

	public function all();
	public function get($table);
	public function from($table);
	public function where($col,$op,$data);
	public function orderByDesc();
	public function runSql($sql);
	public function save($table,array $cols,array $values);

}
