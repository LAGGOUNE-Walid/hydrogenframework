<?php 

namespace src\Classes\Session;
session_start();
session_regenerate_id(true);

use src\Classes\View\View as View;
use src\Classes\Router\Request\Request as Request;
use src\Exceptions\HydrogenExceptions as HydrogenExceptions;
use src\Interfaces\Session\SessionInterface as SessionInterface;

class Session implements SessionInterface {

    /**
     * Description: src\Interfaces\SessionInterface Line: 12
     */
	 public function token() {
        $token = bin2hex(mhash(MHASH_TIGER160, rand()));
        $_SESSION['_token'] = $token;
        return $token;
    }


    /**
     * Description: src\Interfaces\SessionInterface Line:19
     */
    public function verifyToken() {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") { 
                if (!isset($_POST['_token'])) { 
                    throw new HydrogenExceptions("[HTTP] : Invalid csrf token,", 8);
                }
                $request = new Request;
                if ($_SESSION['_token'] !== $request->get('_token')) {
                    throw new HydrogenExceptions("[HTTP] : Invalid csrf token",9);
                }
            }
        } catch (HydrogenExceptions $e) {
            http_response_code(400);
            $view = new View;
            $ex = get_class($e);
            $erorr = $e->getMessage()." error code : #".$e->getCode()." [$ex]";
            $view->show("errors/400", ["error" => $erorr]);
            exit();
        }
    }
    
    /**
     * Description: src\Interfaces\SessionInterface Line:28
     */
    public function set($key, $value) {   
        if ((!is_string($key) || ($key === '')) || is_null($value)) { return false; }
        $_SESSION[$key] = $value;
        if ($this->has($key)) {
            return true;
        }
        return false;
    }

    /**
     * Description: src\Interfaces\SessionInterface Line:36
     */
    public function has($key) {   
        return isset($_SESSION[$key]);
    }
    
    /**
     * Description: src\Interfaces\SessionInterface Line:44
     */
    public function destroy($key = '') {   
        if ($key === '') { 
            $ret = @session_destroy();
            unset($_SESSION);
            $_SESSION = array();
            return $ret;
        }
        if (self::has($key)) {
            session_unset($key);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Description: src\Interfaces\SessionInterface Line:51
     */
    public function getAll() {
        return $_SESSION;
    }

    /**
     * Description: src\Interfaces\SessionInterface Line:58
     */
    public function get($key) {
        $ret = self::has($key) ? $_SESSION[$key] : NULL;
        return $ret;
    }
}