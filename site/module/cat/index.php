<?php
$config = parse_ini_file('../config.ini', true);
$id = $site->getParamInt('id');
if (!$id) $site->redirect($site->moduleUrl);

$cat = $site->db->query('SELECT * FROM ' . $site->module . '_cats' . ' WHERE id = ' . $id . ';')->fetch(PDO::FETCH_ASSOC);
if (empty($cat)) $site->redirect($site->moduleUrl);

$per_page = 10;
$first = $site->getPageItemsFirst($per_page);
$items = $site->db->query(
    'SELECT * FROM ' . $site->module . '_items WHERE cat = ' . $id . ' ORDER BY id LIMIT ' . $first . ',' . $per_page . ';'
)->fetchAll(PDO::FETCH_ASSOC);
$count = $site->db->query('SELECT COUNT(*) FROM ' . $site->module . '_items WHERE cat = ' . $id)->fetchColumn();
$pager = $site->getPager($per_page, $count);