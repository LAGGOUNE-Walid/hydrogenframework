<?php

namespace Kernel\classes\Router;

use Kernel\Interfaces\Router\HttpCache as HttpCacheInterface;
/**
  *
  * Http Cache class created by walidlaggoune 2016
  *
*/

class HttpCache implements HttpCacheInterface {


    public $fileName;

    public $path;

    public $lastModified;

    public $etag;



    public function make(string $fileName) {

      $this->fileName     = $fileName;

      $this->path         = "views/$fileName.php";

      $this->lastModified = $this->getLastModified();

      $this->httpHeaders();

    }




    public function getLastModified() {

      return filemtime($this->path);

    }


    public function generateEtag() {

      $this->etag = mhash(MHASH_TIGER160, $this->lastModified);
      return $this->etag;

    }



    public function httpHeaders() {

      header("Cache-Control: must-revalidate");

      /* https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html 14.29 */

      header("Last-Modified:" .gmdate('D, d M Y H:i:s T',$this->lastModified));

      /* https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html 14.19 */

      header("Etag:" .bin2hex($this->generateEtag()));

      $this->validate();

    }



    public function validate() {

      if( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) AND strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) === $this->lastModified) {

        http_response_code("304");
        exit();

      }

      if( isset($_SERVER['HTTP_IF_NONE_MATCH']) AND bin2hex($_SERVER['HTTP_IF_NONE_MATCH']) === bin2hex($this->etag)) {

        http_response_code("304");
        exit();

      }


    }

}
