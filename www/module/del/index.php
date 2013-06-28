<?php
require('../../core/init.php');
require('../init.php');
$site->setPage(basename(dirname(__FILE__)));

if ($site->getUserAccess() < $config['access']['del']) $site->redirect($site->moduleUrl);

$id = $site->getParamInt('id');
if ($id) {
	$site->db->prepare('DELETE from ' . $site->module . '_items WHERE id = ?')->execute(array($id));
}

if ($site->isAjaxRequest())	$site->ajaxResponse('');
else $site->back();
