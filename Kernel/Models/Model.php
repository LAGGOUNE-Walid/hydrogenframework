<?php

namespace Kernel\Models;

use Kernel\classes\Database\Database as Database;


class Model {

    public $Database = NULL;

    public function __construct() {

      $this->Database = new Database;
      
    }

}
