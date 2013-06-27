<?php
	define ('PAGE', 'module2');
	require_once('../inc/init.inc'); 
	require_once('init.inc');
	
	$cats = sql_get_rows('SELECT * FROM ' . MODULE . '_cats ORDER BY id;');
	
	include(TEMPLATE_PATH . '/main.tpl');            
?>