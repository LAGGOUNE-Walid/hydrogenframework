<?php

namespace Kernel\Interfaces\Router\Request;

interface Request {

  public function get(string $inputName);
  public function info();
  public function getHeader(string $name);
  public function file(string $fileName);
  public function getName();
  public function getExtension();
  public function getMimetype();
  public function getSize();
  public function size();
  public function getTmp();

}
