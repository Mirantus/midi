<?php
require('../../core/init.php');
require('../init.php');
$site->setPage(basename(dirname(__FILE__)));

$per_page = 10;
$first = $site->getPageItemsFirst($per_page);
$items = $site->db->query(
	'SELECT * FROM ' . $site->module . '_cats ORDER BY id LIMIT ' . $first . ',' . $per_page . ';'
)->fetchAll(PDO::FETCH_ASSOC);
$count = $site->db->query('SELECT COUNT(*) FROM ' . $site->module . '_cats')->fetchColumn();
$pager = $site->getPager($per_page, $count);

include($site->layoutPath . '/default.phtml');