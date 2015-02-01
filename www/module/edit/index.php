<?php
require('../../../core/init.php');
require('../init.php');
$site->setPage(basename(dirname(__FILE__)));

if ($site->getUserAccess() < $config['access']['edit']) $site->redirect($site->moduleUrl);

$id = $site->getParamInt('id');
if (!$id) $site->back();

$item = $site->db->query('SELECT * FROM ' . $site->module . '_items' . ' WHERE id = ' . $id . ';')->fetch(PDO::FETCH_ASSOC);
if (empty($item)) $site->back();

$form = new Form();
if (isset($config['cats'])) {
	$cats = $site->db->query('SELECT id,title FROM ' . $site->module . '_cats ORDER BY id;')->fetchAll(PDO::FETCH_ASSOC);
	$form->add('cat', array('title' => 'Рубрика', 'value' => $item['cat']));
}
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
$form->fill();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//initialization
	$form->url->value = str_replace('http://', '', $form->url->value);
	if ($form->url->value != '') $form->url->value = 'http://' . $form->url->value;
	if ($form->phone->value != '') $form->phone->value = Utils::preparePhones($form->phone->value);

	//validation
	if (!empty($form->email->value) && !$form->email->isEmail()) $form->email->error = $form->errors['email'];
	if (!empty($form->url->value) && !$form->url->isUrl()) $form->url->error = $form->errors['url'];
	if (!empty($form->icq->value) && !$form->icq->isIcq()) $form->icq->error = $form->errors['icq'];
	if (!empty($form->price->value) && !$form->price->isInt()) $form->price->error = $form->errors['int'];
	if (isset($config['cats']) && !$form->cat->isInt()) $form->cat->error = $form->errors['cat'];
	if (empty($form->title->value)) $form->title->error = 'Введите пожалуйста заголовок';

	if ($site->isFileUploaded('file')) {
		$form->file->value = Utils::prepareFileName($_FILES['file']['name']);
		$form->file->error = $site->getFileUploadError('file');
	}
	if ($site->isFileUploaded('image')) {
		$form->image->value = Utils::prepareFileName($_FILES['image']['name']);
		$form->file->error = $site->getFileUploadError('image');

		if (!$form->file->error && !Image::GetType($_FILES['image']['tmp_name'])) {
			$form->image->error = 'Изображение должно быть в формате jpg, png или gif';
		}
	}
	if ($site->isFileUploaded('file') && $form->isValid()) {
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $site->moduleItemsPath . '/' . $form->file->value)) {
			$form->file->error = 'Ошибка загрузки файла';
		}
	}
	if ($site->isFileUploaded('image') && $form->isValid()) {
		if (!move_uploaded_file($_FILES['image']['tmp_name'], $site->moduleImagePath . '/' . $form->image->value)) {
			$form->image->error = 'Ошибка загрузки файла';
		}
	}

	//process
	if ($form->isValid()) {
		$formValues = $form->toArray();

		$fields = $values = array();
		$values['id'] = $id;
		foreach ($formValues as $field => $value) {
			if (!isset($config['items'][$field])) continue;
			$fields[] = '`' . $field . '`=:' . $field;
			$values[$field] = $value;
		}
		$st = $site->db->prepare(
			'UPDATE ' . $site->module . '_items SET ' . implode(',', $fields) . ' WHERE id = :id'
		);
		$st->execute($values);

		//image
		if ($site->isFileUploaded('image')) {
			$image = $id . '.'  . Image::GetType($site->moduleImagePath . '/' . $form->image->value);
			rename($site->moduleImagePath . '/' . $form->image->value, $site->moduleImagePath . '/' . $image);
			Image::CreatePreview($site->moduleImagePath . '/' . $image, $site->moduleImagePath . '/thumbs/' . $image, 100);
			$site->db->exec('UPDATE ' . $site->module . '_items SET image="' . $image . '" WHERE id=' . $id);
		}

		if (!$site->isAjaxRequest()) $site->redirect($site->url . '/ok');
	}

	if ($site->isAjaxRequest()) {
		$ajaxResponse = array();
		if (!$form->isValid()) {
			$ajaxResponse['errors'] = $form->getErrors();
		}
		$site->ajaxResponse($ajaxResponse);
	}
}

include($site->layoutPath . '/default.phtml');