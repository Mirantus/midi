<script type="text/javascript" src="js/jquery.lightbox.js"></script>
<link rel="stylesheet" href="css/jquery.lightbox.css" type="text/css" />
<h3><?=$title;?></h3>
<?
	if ($image != '')
		echo '<a href="/data/' . MODULE . '/images/' . $image . '" rel="lightbox"><img src="/data/' . MODULE . '/images/s_' . $image . '" alt="' . $title . '"/></a><br/>';
	if ($text != '') echo 'О себе:<br/>' . $text . '<br/>';
	if ($name != '') echo 'Имя: ' . $name . '<br/>';
	$gender = ($gender == '0') ? 'женский' : 'мужской';
	echo 'Пол: ' . $gender . '<br/>';
	if ($birthday != '0000-00-00') echo convert_sql_to_text_date($birthday);
	if ($phone != '') echo 'Телефон: ' . $phone . '<br/>';
	if ($icq != '0' && $icq != '') echo 'ICQ: ' . $icq . '<br/>';
	if ($url != '') echo '<a href="' . $url . '" target="_blank">' . $url . '</a><br/>';
	if ($email != '') echo '<a href="mailto:' . $email . '">' . $email . '</a><br/>';
	if ($city != '') echo 'Город: ' . $city . '<br/>';
	if ($zip != '') echo 'Почтовый индекс: ' . $zip . '<br/>';
	if ($address != '') echo 'Адрес: ' . $address . '<br/>';
	if ($company != '') echo 'Организация: ' . $company . '<br/>';
	if ($occupation != '') echo 'Род занятий: ' . $occupation . '<br/>';
?>