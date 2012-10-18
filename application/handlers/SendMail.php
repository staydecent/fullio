<?php

class SendMail extends mud\RequestHandler {

    function get($args) {
        echo "Nothing here";
    }

    function post($args) 
    {
        // wat?

        $log = mud\Logger::instance(ROOT.'logs/', mud\Logger::DEBUG);
        $log->info('Testing async');

        return 'Success!';
    }
}

?>