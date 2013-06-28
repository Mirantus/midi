<?php
require('../core/init.php');
$site->setModule(basename(dirname(__FILE__)));

$form = new Form();
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
	$ip = $_SERVER['REMOTE_ADDR'];
	$date = date('Y-m-d');

	//validation
	if (!empty($form->email->value) && !$form->email->isEmail()) $form->email->error = $form->errors['email'];
	if (!empty($form->url->value) && !$form->url->isUrl()) $form->url->error = $form->errors['url'];
	if (!empty($form->icq->value) && !$form->icq->isIcq()) $form->icq->error = $form->errors['icq'];
	if (!empty($form->price->value) && !$form->price->isInt()) $form->price->error = $form->errors['int'];
	if (empty($form->title->value)) $form->title->error = 'Введите пожалуйста заголовок';

	//process
	if ($form->isValid()) {
		$message = '';
		foreach ($form->fields as $field) {
			$message .= $field->title . ': ' . $field->value . "\r\n";
		}
		$site->mail($site->owner, $_SERVER['HTTP_HOST'] . ' - ' . $site->module, $message);
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