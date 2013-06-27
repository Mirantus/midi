<?php
	//initialization
	define ('PAGE', 'users_item');
	require_once('../inc/init.inc'); 
	require_once('init.inc');	
	require_once('../php/time.php'); 
	
	$id = $title = $text = $image = $name = $gender = $birthday = $phone = $icq = $url = $email = $city = $zip = $address = $company = $occupation = $ip = $date = $access = '';
	
	$id = sql_prepare( assign('id') );
	$item_info = sql_get_row('SELECT * FROM ' . MODULE . ' WHERE id = "' . $id . '";');
	if ($item_info)
	{
		$title = $item_info['title'];
		$text = $item_info['text'];	
		$image = $item_info['image'];
		$name = $item_info['name'];
		$gender = $item_info['gender'];
		$birthday = $item_info['birthday'];
		$phone = $item_info['phone'];
		$icq = $item_info['icq'];	
		$url = $item_info['url'];	
		$email = $item_info['email'];
		$city = $item_info['city'];
		$zip = $item_info['zip'];
		$address = $item_info['address'];
		$company = $item_info['company'];
		$occupation = $item_info['occupation'];
		$date = $item_info['date'];
	}
	
	include(TEMPLATE_PATH . '/main.tpl');        
?>