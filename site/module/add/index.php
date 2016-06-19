<?php

if ($site->getUserAccess() < $config['access']['add']) $site->redirect($site->moduleUrl);

$moduleImagePath = $site->webrootPath . '/data/' . $this->module . '/items/';
	
$form = new Form();
if (isset($config['cats'])) {
	$cats = $site->db->query('SELECT id,title FROM ' . $site->module . '_cats ORDER BY id;')->fetchAll(PDO::FETCH_ASSOC);
	$form->add('cat', array('title' => 'Рубрика'));
}
$form->add('title', array('title' => 'Заголовок'));
$form->add('text', array('title' => 'Текст'));
$form->add('price', array('title' => 'Цена'));
$form->add('image', array('title' => 'Изображение'));
$form->add('file', array('title' => 'Файл'));
$form->add('name', array('title' => 'Имя'));
$form->add('phone', array('title' => 'Телефон'));
$form->add('icq', array('title' => 'ICQ'));
$form->add('skype', array('title' => 'Skype'));
$form->add('url', array('title' => 'Сайт'));
$form->add('email', array('title' => 'E-mail'));
$form->add('city', array('title' => 'Город'));
$form->add('zip', array('title' => 'Почтовый индекс'));
$form->add('address', array('title' => 'Адрес'));
$form->add('company', array('title' => 'Организация'));
$form->add('occupation', array('title' => 'Род занятий'));

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
		$form->image->error = $site->getFileUploadError('image');

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
		if (!move_uploaded_file($_FILES['image']['tmp_name'], $moduleImagePath . '/' . $form->image->value)) {
			$form->image->error = 'Ошибка загрузки файла';
		}
	}

	//process
	if ($form->isValid()) {
		$formValues = $form->toArray();
		$formValues['file'] = $form->file->value;
		$formValues['image'] = $form->image->value;
		$formValues['user'] = $_SESSION['current_user']['id'];
		$formValues['ip'] = $_SERVER['REMOTE_ADDR'];
		$formValues['date'] = date('Y-m-d');

		$fields = $placeholders = $values = array();
		foreach ($formValues as $field => $value) {
			if (!isset($config['items'][$field])) continue;
			$fields[] = $field;
			$placeholders[] = ':' . $field;
			$values[$field] = $value;
		}
		$st = $site->db->prepare(
			'INSERT INTO ' . $site->module . '_items (' . implode(',', $fields) . ') values (' . implode(',', $placeholders) . ')'
		);
		$st->execute($values);

		//image
		$id = $site->db->lastInsertId();
		if ($site->isFileUploaded('image')) {
			$image = $id . '.'  . Image::GetType($moduleImagePath . '/' . $form->image->value);
			rename($moduleImagePath . '/' . $form->image->value, $moduleImagePath . '/' . $image);
			Image::CreatePreview($moduleImagePath . '/' . $image, $moduleImagePath . '/thumbs/' . $image, 100);
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