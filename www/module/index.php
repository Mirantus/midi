<?php
require('../../core/init.php');
require('./init.php');

if (isset($config['items'])) {
	$type = isset($config['cats']) ? 'cats' : 'items';

	$per_page = 10;
	$first = $site->getPageItemsFirst($per_page);
	$items = $site->db->query(
		'SELECT * FROM ' . $site->module . '_' . $type . ' ORDER BY id LIMIT ' . $first . ',' . $per_page . ';'
	)->fetchAll(PDO::FETCH_ASSOC);
	$count = $site->db->query('SELECT COUNT(*) FROM ' . $site->module . '_items')->fetchColumn();
	$pager = $site->getPager($per_page, $count);

	$site->pagePath = $site->modulePath . '/' . $type;
} else {
	$items = parse_ini_file($site->moduleItemsPath . '/items.ini', true);
	if ($items) {
		$per_page = 10;
		$pager_info = $site->getPager(10, count($items));
		$items = $pager_info['items'];
		$pager = $pager_info['pager'];
	}
}

include($site->layoutPath . '/default.phtml');