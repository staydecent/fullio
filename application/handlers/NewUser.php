<?php

class NewUser extends mud\RequestHandler {

    function get($args) 
    {
        $file = TEMPLATE_DIR.'form.new_user.html';
        $data = array(
        );
        $this->response(mud\Template::render($file, $data));
    }

    function post($args) 
    {
        require MODELS_DIR.'User.php';

        $file = TEMPLATE_DIR.'form.new_user.html';

        // Check invite code
        $invite = $this->request(array('invite'));

        if (strtolower($invite['invite']) !== 'DABEARS')
        {
            $data = array(
                'error' => 'Invalid Invite Code.'
            );
            $this->response(mud\Template::render($file, $data));

            return FALSE;
        }

        // Proceed

        $form_fields = $this->request(array(
            'email',
            'password',
            'subdomain'
        ));

        // Check email
        $existing_email = Model::factory('User')->where('email', $form_fields['email'])->find_one();
        if ($existing_email)
        {
            $data = array(
                'error' => 'Email in use.'
            );
            $this->response(mud\Template::render($file, $data));

            return FALSE;
        }

        // Check subdomain
        $existing_subdomain = Model::factory('User')->where('subdomain', $form_fields['subdomain'])->find_one();
        if ($existing_subdomain)
        {
            $data = array(
                'error' => 'Subdomain already in use.'
            );
            $this->response(mud\Template::render($file, $data));

            return FALSE;
        }

        // new User
        $user = Model::factory('User')->create();
        $user->email = $form_fields['email'];
        $user->password = $form_fields['password'];
        $user->subdomain = $form_fields['subdomain'];
        $user->created_at = date("Y-m-d H:i:s");

        if ( ! $user->valid_subdomain()) 
        {
            // snarky fuck
            $data = array(
                'error' => 'Invalid/reserved subdomain. Alphanumeric characters only.'
            );
            $this->response(mud\Template::render($file, $data));

            return FALSE;
        }

        // errthangs valid, create
        if ( ! $user->save()) 
        {
            // fucked up
            $log = mud\Logger::instance(STORAGE_DIR.'logs/', mud\Logger::DEBUG);
            $log->fatal('Failed New User: '.print_r($user, TRUE));

            $data = array(
                'error' => 'Oops. We failed to create your account. Please contact support.'
            );
            $this->response(mud\Template::render($file, $data));
        }
        else 
        {
            // log the user in
            $_SESSION['uid'] = $user->id;
            // send welcome email
            $msg = "Thanks for signing up!\r\n\r\n";
            $msg.= "Check out your new Fullio: http://{$user->subdomain}.fullioapp.com\r\n\r\n";
            $msg.= "You can view and edit your account settings here: http://fullioapp.com/user\r\n";
            $msg.= "For help getting started with Fullio, check out the Quickstart Guide from our Documentation:\r\n\r\n";
            $msg.= "http://fullioapp.com/docs/quickstart\r\n\r\n";
            $msg.= "We hope you love your new Fullio!";

            $mail = send_mail($user->email, 'Welcome to Fullio', $msg);

            header("Location: http://".BASE_DOMAIN.'/user');
        }
    }
}