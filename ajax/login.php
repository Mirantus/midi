<?php	//initialization	require_once('../inc/init.inc');	require_once(SITE_PATH . '/php/sql.php');		$login = sql_prepare( assign('login') );	$password = md5( assign('password') );		$user_info = sql_get_row('SELECT * FROM users WHERE title = "' . $login . '" AND password = "' . $password . '"');	if ($user_info != false)	{		$_SESSION['current_user'] = $user_info;		echo $user_info['id'];	}	else echo 0;?>