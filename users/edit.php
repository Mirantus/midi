<?php
	//initialization
	define ('PAGE', 'users_edit');
	require_once('../inc/init.inc'); 
	require_once('init.inc');

	$id = $title = $password = $text = $image = $name = $gender = $birthday = $phone = $icq = $url = $email = $city = $zip = $address = $company = $occupation = $ip = $date = $access = $alert = '';
	$result = true;
	
	$mode = 'add';
	$id = sql_prepare( assign('id') );
	if ($id != '') $mode = 'edit';	
	
	$item_info = sql_get_row('SELECT * FROM ' . MODULE . ' WHERE id = "' . $id . '";');
	
	//view edit
	if ($_SERVER['REQUEST_METHOD'] == 'GET' && $mode == 'edit')
	{
		if ($item_info)
		{
			$title = $item_info['title'];
			$text = $item_info['text'];	
			$image = $item_info['image'];
			$name = $item_info['name'];
			$gender = $item_info['gender'];
			$birthday = $item_info['birthday'];
			$phone = $item_info['phone'];
			$icq = ($item_info['icq'] == 0) ? '': $item_info['icq'];	
			$url = $item_info['url'];	
			$email = $item_info['email'];
			$city = $item_info['city'];
			$zip = $item_info['zip'];
			$address = $item_info['address'];
			$company = $item_info['company'];
			$occupation = $item_info['occupation'];
		}
	}
	
	//post from view and edit
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		//initialization
		$title = sql_prepare( assign('title') );
		$password = assign('password');
		$text = sql_prepare( assign('text') );	
				
		$name = sql_prepare( assign('name') );
		$gender = (assign('gender')) ? 0 : 1;
		$birthday = convert_date_to_sql(assign('birthday'));
		$phone = sql_prepare( assign('phone') );
		$icq = sql_prepare( assign('icq') );
		
		$url = assign('url');	
		$url = str_replace('http://', '', $url); if ($url != '') $url = 'http://' . $url; $url = sql_prepare($url);
		
		$email = sql_prepare( assign('email') );
		$city = sql_prepare( assign('city') );
		$zip = sql_prepare( assign('zip') );
		$address = sql_prepare( assign('address') );
		$company = sql_prepare( assign('company') );
		$occupation = sql_prepare( assign('occupation') );
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('Y-m-d');
		$access = 1;

		//validation
		if ($title == '') $alert = 'логин';
		if ($mode == 'add' && sql_count('SELECT title FROM users WHERE title ="' . $title . '"') ) $alert = 'другой логин. Этот логин уже зарегистрирован';
		if ($mode == 'edit' && sql_count('SELECT title FROM users WHERE title ="' . $title . '" AND id != ' . $_SESSION['current_user']['id']) ) $alert = 'другой логин. Этот логин уже зарегистрирован';
		if ($mode == 'edit' && $_SESSION['current_user']['access'] < 2 && $_SESSION['current_user']['id'] != $id) $alert = 'другое имя пользователя. У вас нет доступа для редактирования.';
		
		if ($alert != '') $result = false;
				
		//process
		if ($result)
		{
			if ($mode == 'add')
			{		
				sql_execute('INSERT IGNORE INTO `' . MODULE . '` (title, password, text, name, gender, birthday, phone, icq, url, email, city, zip, address, company, occupation, ip, date, access) VALUES ("' . $title . '", "' . md5($password) . '", "' . $text . '", "' . $name . '", "' . $gender . '", "' . $birthday . '", "' . $phone . '", "' . $icq . '", "' . $url . '", "' . $email . '", "' . $city . '", "' . $zip . '", "' . $address . '", "' . $company . '", "' . $occupation . '", "' . $ip . '", "' . $date . '", "' . $access . '");');			
				$id = mysql_insert_id();
				$image = assign_image($id);
				sql_execute('UPDATE `' . MODULE . '` SET image = "' . $image . '" WHERE id = ' . $id . ';');
			}
			elseif ($mode == 'edit')
			{			
				$image = assign_image($id);
				if ($password != '') $password = 'password = "' . md5($password) . '", ';
				if ($image != '') $image = 'image = "' . $image . '", ';
				sql_execute('UPDATE `' . MODULE . '` SET title = "' . $title . '", ' . $password . 'text = "' . $text . '", ' . $image . 'name = "' . $name . '", gender = "' . $gender . '", birthday = "' . $birthday . '", phone = "' . $phone . '", icq = "' . $icq . '", url = "' . $url . '", email = "' . $email . '", city = "' . $city . '", zip = "' . $zip . '", address = "' . $address . '", company = "' . $company . '", occupation = "' . $occupation . '", ip = "' . $ip . '", date = "' . $date . '", access = "' . $access . '" WHERE id = ' . $id . ';');
			}
			$_SESSION['current_user']['id'] = $id;
			$_SESSION['current_user']['title'] = $title;
			$_SESSION['current_user']['access'] = $access;
			header('Location: ' . SITE_URL . '/ok.php');
			exit();
		}
		elseif ($alert != '')
		{
			$alert = 'Введите пожалуйста ' . $alert;
			$alert = '<script type="text/javascript">alert("' . $alert . '");</script>' . $alert;
		}
	}	
	
	include(TEMPLATE_PATH . '/main.tpl');            
?>