<?php

namespace mud;

use h2o;

class Template {
    /*
        Spits on user.
    */
    public static function render( $file, $vars = array() ){
        /*
            Uses h2o for jinja/django compatability.
        */
        $vars['base_url'] = BASE_URL;
        $h2o = new h2o($file);
        return $h2o->render($vars);
    }
    
    public static function render_php( $file, $vars = array() ) {
        /*
            If PHP has only 1 thing going for it,
            surely it's templating.
        */
        extract($vars);

        ob_start();
        include $file;
        $out = ob_get_contents();
        ob_end_clean();

        return $out;
    }

    public static function add_filter( $array ) {
        /*
            Wrapper for h2o
        */
        h2o::addFilter($array);
    }
}

?>