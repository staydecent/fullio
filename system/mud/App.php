<?php

namespace mud;

class App {
    /*
        the Mud App Class

        A surefire way to handle methods.
    */
    protected $method,
              $route,
              $handler;
    
    function __construct() {
        $this->method = strtolower( $_SERVER['REQUEST_METHOD'] );
        $this->route  = (isset( $_GET['r'] )) ? trim( $_GET['r'], '/\\' ) : '/';
    }

    public function react( $rules ) {
        /*
            Matches regex to a class(handler).
            returns the handler object.
        */
        $is_matched = false;

        foreach ( $rules as $rule => $handler ) {
            // Append some shit so index.php is cleaner
            $rule = str_replace('/', '\/', $rule);
            $rule = '^' . $rule . '\/?$';

            if ( preg_match( "/$rule/i", $this->route, $matches ) ) {
                $is_matched = true;

                if ( $this->load_handler($handler) ) {
                    $this->handler = new $handler;

                    if ( method_exists($this->handler, $this->method) ) {
                        call_user_func_array(array($this->handler, $this->method), $matches);
                    }
                }
                else {
                    // Failed to load handler
                    print('Failed to load handler:');
                    var_dump($handler);
                }         
            }
        }

        if (!$is_matched) {
            // 4044 shizzz
            $handler = 'ErrorHandler';
            if ($this->load_handler($handler)) {
                $this->handler = new $handler;

                if ( method_exists($this->handler, $this->method) ) {
                    call_user_func_array(array($this->handler, $this->method), array('404 Not Found'));
                }
            }
        }
    }

    private function load_handler( $handler ) {
        /*
            Loads the handler file based on its
            class name and returns true.
        */
        $file = APP_DIR.'handlers/'.$handler.'.php';

        if (is_file($file)) 
        {
            include $file;

            if (class_exists($handler, false)) 
            {
                return true;
            }
            else 
            {
                return false;
            }
        }
        else
        {
            return false;
        } 
    }
}

?>