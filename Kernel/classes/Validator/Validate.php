<?php

namespace Kernel\classes\Validator;
use Kernel\Interfaces\Validator\Validator as ValidatorInterface;
/**
  * Design Pattern : Factory
  * wiki : https://en.wikipedia.org/wiki/Factory_pattern
  * First version at : 2016/7/25 00:00
  * Designed for hd framework :3
*/


class Validate implements ValidatorInterface {


    /**
      *
      * -----------| Variables |------------------------|
      * ------------------------------------------------|
      * @var $rule to store the validation rules        |
      * @var $errors to store errors                    |
      * @var $errorsStyle store the sentences of errors |
      *
    */




    protected $rules;

    public    $errors = [];

    public    $xss   = [] ;

    protected $errorsStyle = [

      "maxError"      =>  "Max length allowed",

      "minError"      =>  "Min length allowed",

      "emptyError"    =>  "This Field is required ",

      "notString"     =>  "This field must be a alphabetic character(s) ",

      "notNum"        =>  "This field must be a numeric character(s) ",

      "notEmail"      =>  "Type of this field must be email ",

      "notUrl"        =>  "Type of this field must be url ",

      "notWorking"     => "The url of this website not working "

    ];





    /* Core code */

    /**
      * @param $rules
    */

    public function __construct(array $rules) {

        $this->rules = $rules;

        $this->compileRules();

    }




    /**
    *
    * compileRules() function used to start Validating the inputs based on @var $rules
    *
    */

    public function compileRules() {


        foreach($this->rules as $inputName => $rules) {

          $inputName  = explode(".",$inputName,2);
          $inputName2   = $inputName[1];
          $nickName  = $inputName[0];


            foreach($rules as $rule) {

                $rule   =   explode(":",$rule);
                $value  =   $rule[1];
                $rule   =   $rule[0];

                switch ($rule) {
                  case 'max':

                      if($this->max($inputName2,$value)) {

                        continue;

                      }else {

                        $error = [$nickName=>"{$this->errorsStyle['maxError']} in {$nickName} is {$value}"];
                        array_push($this->errors,$error);
                        continue;

                      }

                  break;


                  case 'min':

                      if($this->min($inputName2,$value)) {

                          continue;

                      }else {

                        $error = [$nickName=>"{$this->errorsStyle['minError']} in {$nickName} is {$value}"];
                        array_push($this->errors,$error);
                        continue;

                      }

                  break;

                  case 'required':

                      if($value==="true") {

                        if($this->makeItRequired($inputName2)) {

                          continue;

                        }else {

                          $error = [$nickName=>"{$this->errorsStyle['emptyError']} in {$nickName}"];
                          array_push($this->errors,$error);
                          continue;

                        }

                      }else {

                        continue;

                      }

                  break;

                  case 'xss':

                    if($value==="true") {

                      $inputName = $this->cleanXss($inputName2);
                      array_push($this->xss,[$nickName => $inputName]);

                    }else {

                      continue;

                    }

                  break;

                  case 'type' :

                    if($value==="string") {

                      if($this->CheckString($inputName2)) {

                        continue;

                      }else {

                        $error = [$nickName=>"{$this->errorsStyle['notString']} in {$nickName}"];
                        array_push($this->errors,$error);
                        continue;

                      }

                    }


                    if($value==="num") {

                      if($this->CheckNum($inputName2)) {

                        continue;

                      }else {

                        $error = [$nickName=>"{$this->errorsStyle['notNum']} in {$nickName}"];
                        array_push($this->errors,$error);
                        continue;

                      }

                    }

                    if($value==="email") {

                      if ($this->CheckEmail($inputName2)) {

                        continue;

                      }else {

                        $error = [$nickName=>"{$this->errorsStyle['notEmail']} in {$nickName}"];
                        array_push($this->errors,$error);
                        continue;

                      }

                    }

                    if($value==="url") {

                      if($this->checkUrl($inputName2)) {

                        continue;

                      }else {

                        $error = [$nickName=>"{$this->errorsStyle['notUrl']} in {$nickName}"];
                        array_push($this->errors,$error);
                        continue;

                      }

                    }

                    if($value==="Aurl") {

                      if(function_exists("curl_version")) {

                          if((curl_exec(curl_init($inputName2)))) {

                            continue;

                          }else {

                            $error = [$nickName=>"{$this->errorsStyle['notWorking']} in {$nickName}"];
                            array_push($this->errors,$error);
                            continue;

                          }

                    }else {

                      die("Curl Not found");

                    }


                  }


                  break;

                }

            }

        }

    }




    public function max(string $inputName,string $value): bool {

        if(strlen($inputName)>=$value) {

          return false;

        }else {

          return true;

        }

    }




    public function min(string $inputName,string $value): bool {

        if(strlen($inputName)<=$value) {

          return false;

        }else {

          return true;

        }

    }




    public function makeItRequired(string $inputName): bool {

      if (empty($inputName) OR $inputName==="") {

        return false;

      }else {

        return true;

      }

    }




    public function cleanXss(string $inputName) {

      return strip_tags($inputName);

    }




    public function CheckString(string $inputName): bool {

      if(ctype_alpha($inputName)) {

        return true;

      }else {

        return false;

      }

    }




    public function CheckNum(string $inputName): bool {

      if(ctype_digit($inputName)) {


        return true;

      }else {

        return false;

      }

    }




    public function CheckEmail(string $inputName): bool {

      if (filter_var($inputName , FILTER_VALIDATE_EMAIL)) {

        return true;

      }else {

        return false;

      }

    }




    public function checkUrl(string $inputName): bool {

      if(filter_var($inputName,FILTER_VALIDATE_URL)) {

        return true;

      }else {

        return false;

      }

    }





}
