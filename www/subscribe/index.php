<?php
require('../core/init.php');
$site->setModule(basename(dirname(__FILE__)));
$site->setPage('');

$form = new Form();
$form->add('email', array('title' => 'E-mail'));
$form->add('action');
$form->fill();
$result = '';

//validation
if (!$form->email->isEmail()) $form->email->error = $form->errors['email'];
if (!in_array($form->action->value, array('subscribe', 'unsubscribe', 'check'))) $form->action->error = 'Данное действие невозможно';

//process
if ($form->isValid()) {
    $email = $form->email->value;
    $action = $form->action->value;
    $emails = file('data.txt');
    $is_subscribed = in_array($email, $emails);

    if ($action == 'subscribe') {
        if ($is_subscribed) {
            $result = 'Адрес ' . $email . ' уже был внесен в базу раньше.';
        } else {
            file_put_contents('data.txt', "\n" . $form->email->value, FILE_APPEND);
			$message = 'Адрес ' . $email . ' подписан на рассылку сайта Телестрока.ру';
			$site->mail($site->owner, $_SERVER['HTTP_HOST'] . ' - ' . $site->module, $message);
            $result = 'Адрес ' . $email . ' внесен в базу подписки.';
        }
    }
    if ($action == 'unsubscribe') {
        if ($is_subscribed ) {
            unset($emails[array_search($email, $emails)]);
            $result = 'Адрес ' . $email . ' исключен из базы подписки.';
        } else {
            $result = 'Адрес ' . $email . ' не найден в базе подписки.';
        }
    }
    if ($action == 'check') {
        if ($is_subscribed ) {
            $result = 'Адрес ' . $email . ' подписан на рассылку сайта Телестрока.ру';
        } else {
            $result = 'Адрес ' . $email . ' не подписан на рассылку сайта Телестрока.ру';
        }
    }

    if (!$site->isAjaxRequest()) $site->redirect($site->url . '/ok');
}

if ($site->isAjaxRequest()) {
    $ajaxResponse = array();
    if (!$form->isValid()) {
        $ajaxResponse['errors'] = $form->getErrors();
    } else {
        $ajaxResponse['result'] = $result;
    }
    $site->ajaxResponse($ajaxResponse);
}