<?php

class ErrorHandler extends mud\RequestHandler {

    function get($args) 
    {
        header('HTTP/1.0 404 Not Found');

        $file = TEMPLATE_DIR.'error.html';
        $data = array(
            'access' =>true,
            'status'=>$args,
            'msg'=>'We couldn\'t find the page you were looking for.'
        );
        $this->response(mud\Template::render($file, $data));
    }

    function post($args) {
        return false;
    }
}

?>