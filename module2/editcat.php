<?php
	//initialization
	define ('PAGE', 'module2_editcat');
	require_once('../inc/init.inc'); 
	require_once('init.inc');

	$cat = $title = $text = $access = $rate = $alert = '';
	$result = true;
	
	$mode = 'add';
	$cat= sql_prepare( assign('cat') );
	if ($cat != '') $mode = 'edit';	
	
	$cat_info = sql_get_row('SELECT * FROM ' . MODULE . '_cats WHERE id = "' . $cat . '";');
	
	//view edit
	if ($_SERVER['REQUEST_METHOD'] == 'GET' && $mode == 'edit')
	{
		if ($cat_info)
		{
			$title = $cat_info['title'];
			$text = $cat_info['text'];	
		}
	}
	
	//post from view and edit
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		//initialization
		$title = sql_prepare( assign('title') );
		$text = sql_prepare( assign('text') );	
		$access = 1;
		$rate = 350;

		//validation
		if ($text == '') $alert = 'текст';
		if ($title == '') $alert = 'заголовок';
		if ($mode == 'edit' && $_SESSION['current_user']['access'] < 2) $alert = 'другое имя пользователя. У вас нет доступа для редактирования.';
		
		if ($alert != '') $result = false;
				
		//process
		if ($result)
		{
			if ($mode == 'add')
			{		
				sql_execute('INSERT IGNORE INTO `' . MODULE . '_cats` (title, text, access, rate) VALUES ("' . $title . '", "' . $text . '", "' . $access . '", "' . $rate . '");');			
			}
			elseif ($mode == 'edit')
			{			
				sql_execute('UPDATE `' . MODULE . '_cats` SET title = "' . $title . '", text = "' . $text . '", access = "' . $access . '", rate = "' . $rate . '" WHERE id = ' . $cat . ';');
			}	
			if ($_SESSION['current_user']['access'] > 1) exit(header('Location: ' . SITE_URL . '/' . MODULE));		
			exit(header('Location: ' . SITE_URL . '/ok.php'));
		}
		elseif ($alert != '')
		{
			$alert = 'Введите пожалуйста ' . $alert;
			$alert = '<script type="text/javascript">alert("' . $alert . '");</script>' . $alert;
		}
	}	
	
	include(TEMPLATE_PATH . '/main.tpl');   	            
?>