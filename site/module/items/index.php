<?php
$config = parse_ini_file('../config.ini', true);
$per_page = 10;
$first = $site->getPageItemsFirst($per_page);
$items = $site->db->query(
	'SELECT * FROM ' . $site->module . '_items ORDER BY id LIMIT ' . $first . ',' . $per_page . ';'
)->fetchAll(PDO::FETCH_ASSOC);
$count = $site->db->query('SELECT COUNT(*) FROM ' . $site->module . '_items')->fetchColumn();
$pager = $site->getPager($per_page, $count);