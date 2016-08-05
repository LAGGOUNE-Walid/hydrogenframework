<?php

namespace Kernel\Interfaces\Validator;

interface Validator {

  public function compileRules();
  public function max(string $inputName,string $value);
  public function min(string $inputName,string $value);
  public function makeItRequired(string $inputName);
  public function cleanXss(string $inputName);
  public function CheckString(string $inputName);
  public function CheckNum(string $inputName);
  public function CheckEmail(string $inputName);
  public function checkUrl(string $inputName);

}
