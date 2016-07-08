<?php

namespace Kernel\classes\Database;
use Kernel\Interfaces\Database\Database as DatabaseInterface;
use PDO;

class Database implements DatabaseInterface {

	protected $user = "";
	protected $pass = "";
	protected $host = "";
	protected $db = "";
	protected $data;
	protected $select;
	protected $vars = [];
	protected $con;
	protected $table;


	public function __construct() {
		if(!empty($this->host)) {
			return $this->con = new PDO("mysql:host=$this->host;dbname=$this->db","$this->user","$this->pass");
		}
	}

	public function __set($key,$value) {
		return $this->vars[$key] = $value;
	}

	public function __get($key) {
		return $this->vars[$key];
	}

	public function all() {
		return $this->data;
	}

	public function get($table) {

		$select = "SELECT * FROM $table";
		$this->select = $select;
		$query = $this->con->query($select);
		while ($data = $query->fetchAll(PDO::FETCH_OBJ)) {
			$this->data = $data;
		}

		return $this;

	}

	public function from($table) {
			$this->select = "SELECT * FROM $table";
			return $this;
	}

	public function where($col,$op,$data) {

		$this->select .= " WHERE $col $op '$data'";
		$query 		= $this->con->query($this->select);
		while ($data = $query->fetchAll(PDO::FETCH_OBJ)) {
			$this->data = $data;
		}

		return $this;

	}

	public function orderByDesc() {
		$this->data = array_reverse($this->data);
		return $this;
	}

	public function runSql($sql) {

		$query = $this->con->query($sql);
		while ($data = $query->fetchAll(PDO::FETCH_OBJ)) {
			$this->data = $data;
		}

		return $this->data;

	}


	public function save($table,array $cols,array $values){
		$sql = "INSERT INTO $table(";
			foreach ($cols as $col) {
				$sql .= "$col,";
			}

		$sql = rtrim($sql,',');
		$sql .= ") VALUES (";

			foreach ($values as $value) {
				$sql .= "?,";
			}

		$sql = rtrim($sql,',');
		$sql .= ")";
		try{
			$prepare = $this->con->prepare($sql);
			if($prepare->execute($values)===true){

			}else{
				throw new Exception("Faild to insert the data at line 108");
			}
		}catch(Exception $e){
			die($e->getMessage());
		}

	}

	public function __destruct() {
		return $this->con = null;
	}



}
