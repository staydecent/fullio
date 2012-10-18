<?php

class Login extends mud\RequestHandler {

    function __construct() 
    {
        // If session is set, assume correctly logged in
        // BUG: currently redirects to sign up
        if (isset($_SESSION['uid']))
            header("Location: http://".BASE_DOMAIN.'/user');
    }

    function get($args) 
    {
        // Show the login form
        $file = TEMPLATE_DIR.'form.login.html';
        $data = array();
        $this->response(mud\Template::render($file, $data));
    }

    function post($args) 
    {
        require MODELS_DIR.'User.php';

        // get the form post data
        $form_fields = $this->request(array(
            'email',
            'password'
        ));

        $user = Model::factory('User')->where('email', $form_fields['email'])->find_one();

        $correct_login = $user->check_password($form_fields['password']);

        if ($correct_login) 
        {
            // set the session, redirect
            $_SESSION['uid'] = $user->id();
            header("Location: http://".BASE_DOMAIN.'/user');
        }
        else 
        {
            // show error
            $file = TEMPLATE_DIR.'form.login.html';
            $data = array(
                'error' => 'Incorrect login credentials!'
            );
            $this->response(mud\Template::render($file, $data));
        }
    }
}