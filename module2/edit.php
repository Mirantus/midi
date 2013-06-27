<?php
	//initialization
	define ('PAGE', 'module2_edit');
	require_once('../inc/init.inc'); 
	require_once('init.inc');

	$id = $title = $text = $price = $image = $file = $name = $phone = $icq = $url = $email = $city = $zip = $address = $company = $occupation = $user = $ip = $date = $access = $rate = $cat = $alert = '';
	$result = true;
	
	$mode = 'add';
	$id = sql_prepare( assign('id') );
	if ($id != '') $mode = 'edit';	
	
	$item_info = sql_get_row('SELECT * FROM ' . MODULE . ' WHERE id = "' . $id . '";');
	
	$cats_info = sql_get_rows('SELECT * FROM ' . MODULE . '_cats;');
	
	//view edit
	if ($_SERVER['REQUEST_METHOD'] == 'GET' && $mode == 'edit')
	{
		if ($item_info)
		{
			$title = $item_info['title'];
			$text = $item_info['text'];	
			$price = $item_info['price'];
			$image = $item_info['image'];
			$name = $item_info['name'];
			$phone = $item_info['phone'];
			$icq = ($item_info['icq'] == 0) ? '': $item_info['icq'];	
			$url = $item_info['url'];	
			$email = $item_info['email'];
			$city = $item_info['city'];
			$zip = $item_info['zip'];
			$address = $item_info['address'];
			$company = $item_info['company'];
			$occupation = $item_info['occupation'];
			$cat = $item_info['cat'];
		}
	}
	
	//post from view and edit
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		//initialization
		$title = sql_prepare( assign('title') );
		$text = sql_prepare( assign('text') );	
		$price = sql_prepare( assign('price') );
				
		$file = assign_file('file');
		$name = sql_prepare( assign('name') );
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
		$user = $_SESSION['current_user']['id'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('Y-m-d');
		$access = 1;
		$rate = 350;
		$cat = sql_prepare( assign('cat') );

		//validation
		if (!preg_match('/\d+/', $cat)) $alert = 'рубрику';
		if ($occupation == '') $alert = 'род занятий';
		if ($company == '') $alert = 'название организации';
		if ($address == '') $alert = 'адрес';
		if ($zip == '') $alert = 'почтовый индекс';
		if ($city == '') $alert = 'город';
		if (!preg_match('/\w+@\w+\.\w{2,4}/', $email)) $alert = 'email';
		if (!preg_match('/\w+\.\w{2,}/', $url)) $alert = 'адрес сайта';
		if (!preg_match('/\d{5,11}/', $icq)) $alert = 'номер icq';
		if ($phone == '') $alert = 'телефон';
		if ($name == '') $alert = 'имя';
		if (!preg_match('/\d+/', $price)) $alert = 'цену (только цифры)';
		if ($text == '') $alert = 'текст';
		if ($title == '') $alert = 'заголовок';
		if ($mode == 'edit' && $_SESSION['current_user']['access'] < 2 && $_SESSION['current_user']['id'] != $item_info['user']) $alert = 'другое имя пользователя. У вас нет доступа для редактирования.';
		
		if ($alert != '') $result = false;
				
		//process
		if ($result)
		{
			if ($mode == 'add')
			{		
				sql_execute('INSERT IGNORE INTO `' . MODULE . '` (title, text, price, name, phone, icq, url, email, city, zip, address, company, occupation, user, ip, date, access, rate, cat) VALUES ("' . $title . '", "' . $text . '", "' . $price . '", "' . $name . '", "' . $phone . '", "' . $icq . '", "' . $url . '", "' . $email . '", "' . $city . '", "' . $zip . '", "' . $address . '", "' . $company . '", "' . $occupation . '", "' . $user . '", "' . $ip . '", "' . $date . '", "' . $access . '", "' . $rate . '", "' . $cat . '");');			
				$id = mysql_insert_id();
				$image = assign_image($id);
				sql_execute('UPDATE `' . MODULE . '` SET image = "' . $image . '" WHERE id = ' . $id . ';');
				
				$message = 'Заголовок: ' . $title . "\r\n" .
							'Текст: ' . $text . "\r\n" .
							'Цена: ' . $price . "\r\n" .
							'Имя: ' . $name . "\r\n" .
							'Телефон: ' . $phone . "\r\n" .
							'ICQ: ' . $icq . "\r\n" .
							'Сайт: ' . $url . "\r\n" .
							'E-mail: ' . $email . "\r\n" .
							'Город: ' . $city . "\r\n" .
							'Почтовый индекс: ' . $zip . "\r\n" .
							'Адрес: ' . $address . "\r\n" .
							'Организация: ' . $company . "\r\n" .
							'Род занятий: ' . $occupation . "\r\n" .
							'Рубрика: ' . $cat;
				
				require_once(SITE_PATH . '/php/mail.php');
				mailer(OWNER_EMAIL, $_SERVER['HTTP_HOST'] . ':' . MODULE, $message);
			
			}
			elseif ($mode == 'edit')
			{			
				$image = assign_image($id);
				if ($image != '') $image = 'image = "' . $image . '", ';
				sql_execute('UPDATE `' . MODULE . '` SET title = "' . $title . '", text = "' . $text . '", price = "' . $price . '", ' . $image . 'name = "' . $name . '", phone = "' . $phone . '", icq = "' . $icq . '", url = "' . $url . '", email = "' . $email . '", city = "' . $city . '", zip = "' . $zip . '", address = "' . $address . '", company = "' . $company . '", occupation = "' . $occupation . '", user = "' . $user . '", ip = "' . $ip . '", date = "' . $date . '", access = "' . $access . '", rate = "' . $rate . '", cat = "' . $cat . '" WHERE id = ' . $id . ';');
			}			
			
			if ($_SESSION['current_user']['access'] > 1) exit(header('Location: ' . SITE_URL . '/' . MODULE . '/cat.php?cat=' . $cat));
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