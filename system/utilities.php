<?php

/**
 * Send mail via Requests
 *
 * @return mixed false or Response
 */
function send_mail($to, $subject, $text)
{
    require SYSTEM_DIR.'Requests/library/Requests.php';

    Requests::register_autoloader();

    $signature = "\r\n\r\nIf you have any questions or issues, email me at support@fullioapp.com. \r\n\r\nThanks, \r\nAdrian Unger, Founder Fullio App";

    $auth = base64_encode('api:'.MAILGUN_KEY);
    $headers = array('Authorization' => 'Basic '.$auth);
    $post = array('from' => 'Fullio App <support@fullioapp.com>',
                      'to' => $to,
                      'subject' => $subject,
                      'text' => $text.$signature
                      );

    $response = Requests::post('https://api.mailgun.net/v2/fullioapp.mailgun.org/messages', $headers, $post);

    if ( ! $response->success)
    {
        return FALSE;
    }
    else 
    {
        return $response;
    }
}

function str_random($length = 5, $type = 'alnum') 
{
    switch ($type)
    {
        case 'alpha':
            $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        case 'alnum':
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        default:
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
}

/**
 * Header Redirect
 *
 * Header redirect in two flavors
 * For very fine grained control over headers, you could use the Output
 * Library's set_header() function.
 *
 * @param   string  the URL
 * @return  string
 */
function redirect($uri = '', $http_response_code = 302)
{
    if ( ! preg_match('#^https?://#i', $uri))
    {
        $uri = BASE_URL . ltrim($uri, '/');
    }

    // echo $uri;

    header('Location: '.$uri, TRUE, $http_response_code);

    exit;
}
