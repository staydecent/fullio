<?php 

namespace mud;

abstract class RequestHandler {

    function __construct() {}

    public function response($str) {
        echo $str;
    }

    public function request($arg) {
        /*
            Returns the value of a _GET or _POST variable
            matching $arg.
        */
        if (is_array($arg)) {
            $array = array();
            foreach ($arg as $a) {
                if (array_key_exists($a, $_GET) && !empty($_GET[$a]))
                    $array[$a] = $_GET[$a];
                elseif (array_key_exists($a, $_POST) && !empty($_POST[$a]))
                    $array[$a] = $_POST[$a];
            }
            return $array;
        }
        else {
            if (array_key_exists($arg, $_GET) && !empty($_GET[$arg]))
                return $_GET[$arg];
            elseif (array_key_exists($arg, $_POST) && !empty($_POST[$arg]))
                return $_POST[$arg];
        }

        return false;
    }

    public function get_current_user()
    {
        require_once MODELS_DIR.'User.php';

        if ( ! isset($_SESSION['uid']))
        {
            return FALSE;
        }

        $user = \Model::factory('User')->where('id', $_SESSION['uid'])->find_one();

        if ( ! $user)
        {
            return FALSE;
        }
        else 
        {
            return $user;
        }
    }

    /*
        Just for semantics.
    */
    protected function get($args) {

    }

    protected function post($args) {
        
    }

    protected function put($args) {
        
    }

    protected function head($args) {
        
    }

    protected function options($args) {
        
    }

    protected function delete($args) {
        
    }
}

?>