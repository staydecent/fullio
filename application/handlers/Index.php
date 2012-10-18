<?php

class Index extends mud\RequestHandler {
    /*
        Show the main site.
    */
    function get($args) {
        $file = TEMPLATE_DIR.'home.html';
        $data = array(
        );
        $this->response(mud\Template::render($file, $data));
    }
}

?>