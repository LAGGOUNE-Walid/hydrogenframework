<?php

namespace Kernel\classes\Router\Request;

use Kernel\classes\Router\Request\File;

use Kernel\Interfaces\Router\Request\Request as RequestInterface;

class Request implements RequestInterface {

    public $fileData = NULL;
    public $File;


    public function get(string $inputName) {

      $data = isset($_POST[$inputName]) ? $_POST[$inputName] : NULL;
      /**
        * for php 7
        * $data = $_POST[$inputName] ?? $_POST[$inputName]
      */

      return $data;

    }



    public function info() {

      return apache_request_headers();

    }



    public function getHeader(string $name) {

      if(isset(apache_response_headers()[$name])) {

        return apache_response_headers()[$name];

      }

      if(isset($_SERVER[$name])) {

        return $_SERVER[$name];

      }

      return null;

    }



    public function file(string $fileName) {

      if(empty($_FILES[$fileName]["name"])) {

        return NULL;

      }

      $this->fileData = $_FILES[$fileName];

      $this->File     = new File;

      return $this;

    }


    public function getName() {

      return $this->File->getName($this->fileData);

    }

    public function getExtension() {

      return $this->File->getExtension($this->fileData);

    }

    public function getMimetype() {

      return $this->File->getMimetype($this->fileData);

    }

    public function getSize() {

      return $this->File->getSize($this->fileData);

    }

    public function size() {

      return $this->File->size($this->fileData);

    }

    public function getTmp() {

      return $this->File->getTmp($this->fileData);

    }


}
