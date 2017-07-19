<?php
namespace src\Classes\Router\Request;

use src\Classes\Router\Request\File;
use src\Interfaces\Router\Request\RequestInterface as RequestInterface;

class Request implements RequestInterface {

  /**
   * @var fileData used to store the file data
  */
  public $fileData = NULL;

  /**
   * @var File used to store the file Object
   */
  public $File;

  /**
   * Description: src/Interfaces/Router/RequestInterface.php Line:13
  */
  public function get($inputName) {
    $data = isset($_POST[$inputName]) ? $_POST[$inputName] : NULL;
    return $data;
  }

  /**
     * Description: src/Interfaces/Router/RequestInterface.php Line:20
  */
  public function info() {
    return apache_request_headers();
  }

  /**
   * Description: src/Interfaces/Router/RequestInterface.php Line:27
  */
  public function getHeader($name) {
     if(!is_null(@apache_request_headers()[$name])) {
        return apache_request_headers()[$name];
    }
    return null;
  }
 /**
   * Description: src/Interfaces/Router/RequestInterface.php Line:35
  */
  public function putHeader($key, $value) {
    header("$key:$value");
    return true;
  }

  /**
   * Description: src/Interfaces/Router/RequestInterface.php Line:34
  */
  public function file($fileName) {
    if(empty($_FILES[$fileName]["name"])) {
      return NULL;
    }
    $this->fileData = $_FILES[$fileName];
    $this->File     = new File;
    return $this;
  }

  /**
   * Description: src/Interfaces/Router/RequestInterface.php Line:42
  */
  public function getName() {
    return $this->File->getName($this->fileData);
  }

  /**
    * Description: src/Interfaces/Router/RequestInterface.php Line:49
  */
  public function getExtension() {
    return $this->File->getExtension($this->fileData);
  }

  /**
    * Description: src/Interfaces/Router/RequestInterface.php Line:56
  */
  public function getMimetype() {
    return $this->File->getMimetype($this->fileData);
  }

  /**
    * Description: src/Interfaces/Router/RequestInterface.php Line:63
  */
  public function getSize() {
    return $this->File->getSize($this->fileData);
  }

  /**
    * Description: src/Interfaces/Router/RequestInterface.php Line:70
  */
  public function size() {
    return $this->File->size($this->fileData);
  }

  /**
    * Description: src/Interfaces/Router/RequestInterface.php Line:77
  */
  public function getTmp() {
    return $this->File->getTmp($this->fileData);
  }

}