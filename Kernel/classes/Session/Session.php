<?php

namespace Kernel\classes\Session;
session_start();
session_regenerate_id(true);
use Kernel\Interfaces\Session\Session as SessionInterface;

class Session implements SessionInterface {
	
	 public function token()
    {
        $token = bin2hex(mhash(MHASH_TIGER160, rand()));
        $_SESSION['_token'] = $token;
        return $token;
    }

    public function verifyToken()
    {
        if ($_SERVER["REQUEST_METHOD"]==="POST") { 
            if (!isset($_POST['_token'])) { 
                http_response_code(400);
                echo "<h2 style='color:red; text-align:center;'>"."Invalid csrf Token!"."<h2>";
                die();
            }else{
                    if ($_SESSION['_token']!==$_POST['_token']) {
                    http_response_code(400);
                    echo "<h2 style='color:red; text-align:center;'>"."Invalid csrf Token!"."<h2>";
                    die();                  
                }
            }
        }
    }

    
    public function set($key, $value)
    {   
        if ((!is_string($key) || ($key === '')) || is_null($value)) { return false; }
        $_SESSION[$key] = $value;
        return true;
    }


    public function has($key)
    {   
        return isset($_SESSION[$key]);
    }
    
    public function destroy($key = '')
    {   
        if ($key === '') { 
            $ret = @session_destroy();
            unset($_SESSION);
            $_SESSION = array();
            session_regenerate_id(true);
            return $ret;
        }
        if (self::has($key)) {
            session_unset($key);
            return true;
        } else {
            return false;
        }
    }

    public function getAll()
    {
        return $_SESSION;
    }

    public function get($key)
    {
        $ret = self::has($key) ? $_SESSION[$key] : NULL;
        return $ret;
    }

}