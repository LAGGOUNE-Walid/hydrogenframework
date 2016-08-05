<?php

/**
  *
  * Created at : 2016/7/2 18:30
  * By         : walid laggoune
  * email      : walidlaggoune159@gmail.com
  * User model used for users logins Based on hd database class
  *
*/

namespace Kernel\Models\User;

use Kernel\Models\Model as Model;

class User extends Model {


      protected $configs;

      protected $passwords = [];

      public function getConfigs() {

        $openIniFile = parse_ini_file("Kernel/config.ini" ,TRUE);
        return $this->configs = $openIniFile['Auth'];

      }



      /**
        *
        * @var $username
        * @var $password
        * @return boolean
        *
      */

      public function canLogin (string $username = NULL, string $password = NULL): bool {

        $this->getConfigs();

        $table      = $this->configs['table'];
        $userCol    = $this->configs['usernameColumn'];
        $passCol    = $this->configs['passwordColumn'];

        if (!$this->Database->from($table)->where($userCol,"=",$username)->all()) {

          return false;

        }

        $users = $this->Database->from($table)->where($userCol,"=",$username)->all();

        foreach($users as $user) {

        array_push($this->passwords,$user->$passCol);
        continue;

        }

        if(!in_array($password,$this->passwords)) {

          return false;

        }

        return true;


      }


}
