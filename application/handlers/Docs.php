<?php

class Docs extends mud\RequestHandler {

    function get($page) 
    {
        if ( ! strstr($page, '/'))
        {
            $page = $page.'/home';
        }

        $file = TEMPLATE_DIR.$page.'.html';
        $data = array(
            'page' => $page,
            'title'=> ucwords(str_replace('docs/', '', $page)),
            'user' => $this->get_current_user()
        );
        $this->response(mud\Template::render($file, $data));
    }
}