<?php

class UserAccount extends mud\RequestHandler {

    public $user,
           $found,
           $sets;

    function __construct() 
    {
        require MODELS_DIR.'Set.php';
        require MODELS_DIR.'User.php';

        // no session, fack off
        if (!isset($_SESSION['uid']))
            header("Location: http://".BASE_DOMAIN.'/login');

        $this->user = Model::factory('User')->find_one($_SESSION['uid']);
        $this->sets = $this->user->sets()->find_many();
    }

    function get($args) 
    {
        $user_array = $this->user->as_array();
        $user_array['name'] = $this->user->get_name();
        $user_array['url'] = $this->user->get_url();

        $file = TEMPLATE_DIR.'user.html';
        $data = array(
            'user' => $user_array
        );
        $this->response(mud\Template::render($file, $data));
    }

    function post($args) 
    {
        $form = $this->request(array(
            'name',
            'email',
            'subdomain',
            'bio',
            'password'
        ));

        // check if password was entered
        if (!isset($form['password']))
            $this->user->password = "";

        // add form values, boom
        $this->user->set_properties($form);
        $this->user->save(); // because _id is set, update

        // make the data more accessible to templates
        $user_array = $this->user->get_properties();
        $user_array['name'] = $this->user->get_name();
        $user_array['url'] = $this->user->get_url();

        $file = TEMPLATE_DIR.'user.html';
        $data = array(
            'user' => $user_array,
            'msg' => 'Your settings have been updated!'
        );
        $this->response(mud\Template::render($file, $data));
    }
}

?>