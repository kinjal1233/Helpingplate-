<?php
declare(strict_types=1);
// BASE_URL matches the nested XAMPP folder currently used for this installation.
define('APP_NAME', 'HelpingPlate');
define('BASE_URL', '/Helpingplate/HelpingPlate_Complete_Project');
define('ROOT_PATH', dirname(__DIR__));
date_default_timezone_set('Asia/Kolkata');
if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params(['httponly'=>true, 'secure'=>$secure, 'samesite'=>'Lax']);
    session_start();
}

// Configure a real sender address for password reset emails. PHP mail() must be set up on the server.
if (!defined('HP_MAIL_FROM')) define('HP_MAIL_FROM', 'no-reply@helpingplate.local');
if (!defined('HP_MAIL_FROM_NAME')) define('HP_MAIL_FROM_NAME', 'HelpingPlate');
