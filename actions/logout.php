<?php
require_once __DIR__.'/../includes/bootstrap.php';
if(is_logged_in()) log_activity((int)current_user()['id'],'logout','User logged out');
$_SESSION=[]; if(ini_get('session.use_cookies')){$p=session_get_cookie_params();setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']);} session_destroy(); header('Location: '.url('index.php')); exit;
