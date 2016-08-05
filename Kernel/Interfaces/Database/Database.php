<?php

namespace Kernel\Interfaces\Database;

interface Database {

	public function all();
	public function get(string $table);
	public function from(string $table);
	public function where(string $col,string $op,string $data);
	public function orderByDesc();
	public function runSql(string $sql);
	public function save(string $table,array $cols,array $values);

}
