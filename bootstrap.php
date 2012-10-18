<?php

$some_name = session_name("fullioapp");
session_set_cookie_params(0, '/', '.fullioapp.com');
session_start();

// dirs
define('ROOT', dirname(__FILE__) . '/');

define('APP_DIR',       ROOT.'application/');
define('ASSETS_DIR',    ROOT.'assets/');
define('STORAGE_DIR',   ROOT.'storage/');
define('SYSTEM_DIR',    ROOT.'system/');

define('CONFIG_DIR',    APP_DIR.'config/');
define('HANDLER_DIR',   APP_DIR.'handlers/');
define('MODELS_DIR',    APP_DIR.'models/');
define('TEMPLATE_DIR',  APP_DIR.'templates/');

// Load config
require CONFIG_DIR.'environment.php'; // not in git
require CONFIG_DIR.'urls.php';

// domains, set in environment.php
define('BASE_URL', 'http://'.BASE_DOMAIN.'/');
// aws
define('S3_BUCKET', 'SAVE_YOUR_IMAGES_HERE');
// Mailgun
define('MAILGUN_KEY', 'YOUR_MAILGUN_KEY');

