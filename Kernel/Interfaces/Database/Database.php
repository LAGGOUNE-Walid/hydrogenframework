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
	public function count();
	public function limit(int $limit);
	public function in(string $first,string $last);
	public function mWhere(array $where);
	public function JsonResponse(string $tableName);

}
