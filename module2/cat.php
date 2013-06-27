<?php
	define ('PAGE', 'module2_cat');
	require_once('../inc/init.inc'); 
	require_once('init.inc');
	require_once('../inc/pager.inc');
	
	$cat = mysql_real_escape_string(assign('cat'));
	
	$items = sql_get_rows('SELECT * FROM ' . MODULE . ' WHERE cat="' . $cat . '" ORDER BY id;');
	if ($items)
	{
		$pager_info = pager($items, 3);
		$items = $pager_info['items'];
		$pager = $pager_info['pager'];
	}
	
	include(TEMPLATE_PATH . '/main.tpl');            
?>