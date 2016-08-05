<?php

namespace Kernel\classes\Validator;


/**
  * Design Pattern : Factory
  * wiki : https://en.wikipedia.org/wiki/Factory_pattern
  * First version at : 2016/7/25 00:00
  * Designed for hd framework :3
*/

class Factory {


    public function make(array $rules) {

      return new Validate($rules);

    }

}
