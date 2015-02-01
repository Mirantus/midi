<?php
require('../../../core/init.php');
require('../init.php');
$site->setPage(basename(dirname(__FILE__)));

$id = $site->getParamInt('id');
if (!$id) $site->redirect($site->moduleUrl);

if (isset($config['items'])) {
	$item = $site->db->query('SELECT * FROM ' . $site->module . '_items' . ' WHERE id = ' . $id . ';')->fetch(PDO::FETCH_ASSOC);
} else {
	$items = parse_ini_file($site->moduleItemsPath . '/items.ini', true);
	$item = $items[$id];
}

if (empty($item)) $site->redirect($site->moduleUrl);

$form = new Form();
$form->add('title', array('title' => 'Заголовок', 'value' => $item['title']));
$form->add('text', array('title' => 'Текст', 'value' => $item['text']));
$form->add('price', array('title' => 'Цена', 'value' => $item['price']));
$form->add('image', array('title' => 'Изображение', 'value' => $item['image']));
$form->add('file', array('title' => 'Файл', 'value' => $item['file']));
$form->add('name', array('title' => 'Имя', 'value' => $item['name']));
$form->add('phone', array('title' => 'Телефон', 'value' => $item['phone']));
$form->add('icq', array('title' => 'ICQ', 'value' => $item['icq']));
$form->add('skype', array('title' => 'Skype', 'value' => $item['skype']));
$form->add('url', array('title' => 'Сайт', 'value' => $item['url']));
$form->add('email', array('title' => 'E-mail', 'value' => $item['email']));
$form->add('city', array('title' => 'Город', 'value' => $item['city']));
$form->add('zip', array('title' => 'Почтовый индекс', 'value' => $item['zip']));
$form->add('address', array('title' => 'Адрес', 'value' => $item['address']));
$form->add('company', array('title' => 'Организация', 'value' => $item['company']));
$form->add('occupation', array('title' => 'Род занятий', 'value' => $item['occupation']));
$form->add('date', array('value' => $item['date']));

if (isset($config['comments'])) {
	$comments = $site->db->query(
        'SELECT * FROM ' . $site->module . '_comments' . ' WHERE item = ' . $id . ' ORDER BY id;'
    )->fetchAll(PDO::FETCH_ASSOC);
}

include($site->layoutPath . '/default.phtml');