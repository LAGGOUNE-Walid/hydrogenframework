<?php
namespace src\Classes\Router\Request;

class File {

    public function getName($fileData) {
      $info = explode(".",$fileData["name"]);
      $info = array_reverse($info);
      return end($info);
    }

    public function getExtension($fileData) {
      $info = explode(".",$fileData["name"]);
      return end($info);
    }

    public function getMimetype($fileData) {
      return mime_content_type($fileData["tmp_name"]);
    }
    public function getSize($fileData) {
      return $fileData['size']/pow(1024,2);
    }
    public function size($fileData) {
      return $fileData['size'];
    }
    public function getTmp($fileData) {
      return $fileData['tmp_name'];
      }
}