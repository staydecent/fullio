<?php 

namespace mud;

class Event {
    // Provided by Danillo César de O. Melo
    // https://github.com/danillos/fire_event/blob/master/Event.php
    private static $instance;
  
    private $hooks = array();
  
    private function __construct() { }
    private function __clone() { }
  
    public static function hook($hook_name, $fn) {
        /*
            Hook into an event with a function.
        */
        $instance = self::get_instance();
        $instance->hooks[$hook_name][] = $fn;
    }
  
    public static function fire($hook_name, $params = NULL) {
        /*
            Register an event, and call any function
            that's been hooked.
        */
        $instance = self::get_instance();
        if (array_key_exists($hook_name, $instance->hooks)) {
            foreach ($instance->hooks[$hook_name] as $fn) {
                if (is_array($fn) || is_string($fn))
                    call_user_func_array($fn, array(&$params));
            }
        }
    }
  
    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new Event();
        }
        return self::$instance;
    }
}

?>