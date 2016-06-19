<?php
$config = parse_ini_file('../config.ini', true);
$site->setPage(basename(dirname(__FILE__)));

if ($site->getUserAccess() < $config['access']['addcomment']) $site->back();

$item = $site->getParamInt('item');
if (!$item) $site->back();

$form = new Form();
$form->add('item', array('value' => $item));
$form->add('text', array('title' => 'Текст'));
$form->add('name', array('title' => 'Имя'));
$form->add('email', array('title' => 'E-mail'));
$form->fill();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//validation
	if (!empty($form->email->value) && !$form->email->isEmail()) $form->email->error = $form->errors['email'];
	if (empty($form->name->value)) $form->name->error = 'Введите пожалуйста ваше имя';
	if (empty($form->text->value)) $form->text->error = 'Введите пожалуйста комментарий';

	//process
	if ($form->isValid()) {
		$formValues = $form->toArray();
		$formValues['user'] = $_SESSION['current_user']['id'];
		$formValues['ip'] = $_SERVER['REMOTE_ADDR'];
		$formValues['date'] = date('Y-m-d');

		$fields = $placeholders = $values = array();
		foreach ($formValues as $field => $value) {
			if (!isset($config['comments'][$field])) continue;
			$fields[] = $field;
			$placeholders[] = ':' . $field;
			$values[$field] = $value;
		}
		$st = $site->db->prepare(
			'INSERT INTO ' . $site->module . '_comments (' . implode(',', $fields) . ') values (' . implode(',', $placeholders) . ')'
		);
		$st->execute($values);
		if (!$site->isAjaxRequest()) $site->redirect($site->url . '/ok');
	}

	if ($site->isAjaxRequest()) {
		$ajaxResponse = array();
		if (!$form->isValid()) {
			$ajaxResponse['errors'] = $form->getErrors();
		} else {
			$ajaxResponse['result'] = 'Спасибо, ваш комментарий принят.';
		}
		$site->ajaxResponse($ajaxResponse);
	}
}