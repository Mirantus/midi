<?php
	define ('PAGE', 'users_help');
	require_once('../inc/init.inc'); 
	require_once('init.inc');
	
	$email = $alert = '';
	$result = true;
	
	//post from view and edit
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		//initialization
		$email = sql_prepare( assign('email') );
		$item_info = sql_get_row('SELECT * FROM ' . MODULE . ' WHERE email = "' . $email . '";');
		
		//validation
		if (!preg_match('/\w+@\w+\.\w{2,4}/', $email)) $alert = 'Введите пожалуйста email';
		if ( $item_info === false )  $alert = 'Пользователей с таким адресом не зарегистрировано';
		if ($alert != '') $result = false;
		
		//process
		if ($result)
		{
			$password = mt_rand(0, 9999999999);
			sql_execute('UPDATE `' . MODULE . '` SET password = "' . md5($password) . '" WHERE id = "' . $item_info['id'] . '";');
			$message = 'Доступ к сайту ' . $_SERVER['HTTP_HOST'] . ': ' . "\r\n\r\n" . 'Логин: ' . $item_info['title'] . "\r\n" . 'Пароль: ' . $password . "\r\n\r\n" . 'После входа, вы можете изменить пароль на странице ' . MODULE_URL .'/edit.php?id=' . $item_info['id']; 
							
			require_once(SITE_PATH . '/php/mail.php');
			mailer($email, 'Пароль для ' . $_SERVER['HTTP_HOST'], $message);
			
			exit(header('Location: ' . SITE_URL . '/ok.php'));
		}
		elseif ($alert != '')
		{
			$alert = '<script type="text/javascript">alert("' . $alert . '");</script>' . $alert;
		}
	}
	
	include(TEMPLATE_PATH . '/main.tpl'); 	         
?>