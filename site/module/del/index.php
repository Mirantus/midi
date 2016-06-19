<?php
$config = parse_ini_file('../config.ini', true);
if ($site->getUserAccess() < $config['access']['del']) $site->redirect($site->moduleUrl);

$id = $site->getParamInt('id');
if ($id) {
	$site->db->prepare('DELETE from ' . $site->module . '_items WHERE id = ?')->execute(array($id));
}

if ($site->isAjaxRequest())	$site->ajaxResponse('');
else $site->back();