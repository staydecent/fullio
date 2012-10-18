<?php 

// urls
$urls = array(
    '/'             => 'Index',
    'user'          => 'UserAccount',
    'user/new'      => 'NewUser',
    'login'         => 'Login',
    'logout'        => 'Logout',
    'mail'          => 'NewSet',
    'send'          => 'SendMail',
    'docs'          => 'Docs',
    'docs/([a-zA-Z0-9_]+)' => 'Docs',
    'test'          => 'Test',
    'error/([a-zA-Z0-9_]+)?' => 'ErrorHandler'
);