<?php
	define ('PAGE', 'users');
	require_once('../inc/init.inc'); 
	require_once('init.inc');
	require_once('../inc/pager.inc');
	
	$items = sql_get_rows('SELECT * FROM ' . MODULE . ' ORDER BY id;');
	if ($items)
	{
		$pager_info = pager($items, 3);
		$items = $pager_info['items'];
		$pager = $pager_info['pager'];
	}
	
	include(TEMPLATE_PATH . '/main.tpl'); 	            
?>