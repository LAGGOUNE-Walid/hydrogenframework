<?php

namespace Kernel\Interfaces\Router;

interface HttpCache {

  public function make(string $filename);
  public function getLastModified();
  public function generateEtag();
  public function httpHeaders();
  public function validate();

}
