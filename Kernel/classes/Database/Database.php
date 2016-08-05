<?php

namespace Kernel\classes\Database;

use Kernel\Interfaces\Database\Database as DatabaseInterface;

use Kernel\classes\Database\Json as JsonResponse;

use PDO;

use Exception;





class Database implements DatabaseInterface {

	protected $data;

	protected $select;

	protected $vars = [];

	protected $con;

	protected $table;




		public function __construct() {

				$connectionInfo = parse_ini_file("Kernel/config.ini",true);
				$host 					= $connectionInfo['Database']['host'];
				$dbname 				= $connectionInfo['Database']['databaseName'];
				$user 					= $connectionInfo['Database']['user'];
				$password 			= $connectionInfo['Database']['password'];
				$port 					= $connectionInfo['Database']['port'];
				return $this->con = new PDO("mysql:host=$host;dbname=$dbname;port=$port",$user,$password);

		}




		public function all () {

			return $this->data;

		}




		public function get (string $table) {

			$select = "SELECT * FROM $table";
			$this->select = $select;
			$query = $this->con->query($select);
			while ($data = $query->fetchAll(PDO::FETCH_OBJ)) {
				$this->data = $data;
			}

			return $this;

		}





		public function from(string $table) {

				$this->select = "SELECT * FROM $table";
				return $this;

		}





		public function where(string $col,string $op,string $data) {

			$this->select 	.= " WHERE $col $op '$data'";

			$query 					= $this->con->query($this->select);

			while ($data 		= $query->fetchAll(PDO::FETCH_OBJ)) {

				$this->data = $data;

			}

			return $this;

		}





		public function orderByDesc() {

			$run = (!empty($this->data)) ? $this->data = array_reverse($this->data) : NULL ;

			return $this;

		}





		public function runSql(string $sql) {

			$query = $this->con->query($sql);

			while ($data = $query->fetchAll(PDO::FETCH_OBJ)) {

				$this->data = $data;

			}

			return $this->data;

		}





		public function save(string $table,array $cols,array $values) {

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

					return true;

				}else{

					throw new Exception("Faild to insert the data .");

				}

			}catch(Exception $e)	{

				die($e->getMessage());

			}

		}




		public function count(): int {

			return sizeof($this->data);

		}

		public function limit(int $limit) {

			$run = (!empty($this->data)) ? $this->data = array_slice($this->data, 0, $limit) : NULL ;
			return $this;

		}


		public function in(string $first,string $last) {

			$this->select .= " WHERE $first IN ('$last')";

			$query 					= $this->con->query($this->select);

			while ($data 		= $query->fetchAll(PDO::FETCH_OBJ)) {

				$this->data = $data;

			}

			return $this;

		}


		public function mWhere(array $where) {


			$this->select .= " WHERE ".array_shift($where);

			$this->select .= " AND (";

			foreach($where as $w) {

				$this->select .= $w;

			}

			$this->select .= ")";

			$query 					= $this->con->query($this->select);

			while ($data 		= $query->fetchAll(PDO::FETCH_OBJ)) {

				$this->data = $data;

			}

			return $this;

		}


		public function JsonResponse(string $tableName) {

			$json = new Json;
			return $json->write($tableName);

		}

		public function __destruct() {

			return $this->con = null;

		}



}
