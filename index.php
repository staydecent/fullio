<?php

require 'bootstrap.php';

// load Mud
require SYSTEM_DIR.'mud/Event.php';
require SYSTEM_DIR.'mud/App.php';
require SYSTEM_DIR.'mud/RequestHandler.php';
require SYSTEM_DIR.'mud/Template.php';
require SYSTEM_DIR.'mud/Logger.php';
// 3rd party
require SYSTEM_DIR.'utilities.php';
require SYSTEM_DIR.'h2o-php/h2o.php';
require SYSTEM_DIR.'idiorm/idiorm.php';
require SYSTEM_DIR.'paris/paris.php';

// connect db
require CONFIG_DIR.'db.php';

// start the app
$mud = new mud\App;

// handle dem wildcard subdomains!
$subdomain = str_replace('.'.BASE_DOMAIN, '', $_SERVER["HTTP_HOST"]);

if ($subdomain !== BASE_DOMAIN 
&& $subdomain !== 'www'
&& $subdomain !== 'blog'
&& $subdomain !== 'localhost') 
{
    define('SUBDOMAIN', $subdomain);
    $mud->react(array('(.)' => 'Fullio')); 
}
else 
{
    $mud->react($urls);   
}