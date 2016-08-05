<?php


namespace Kernel\classes\Database;
use Kernel\classes\Database\Database as Database;

class Json extends Database {


  public function write(string $tableName) {

    return json_encode($this->get($tableName)->all());

  }


}
