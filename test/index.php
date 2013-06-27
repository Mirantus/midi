<?php
	define ('PAGE', 'index');
	require_once('../inc/init.inc'); 
	require_once('init.inc');
	require_once('../inc/pager.inc');
	
	$items = sql_get_rows('SELECT * FROM ' . MODULE . ' ORDER BY id;');
	
	
		foreach ($items as $item_id => $item_params)
		{
			$item_params['title'] = preg_replace ( '/\(.*\)/', '', $item_params['title']);
			$item_params['title'] = preg_replace ( '/\[.*\]/', '', $item_params['title']);
			if (strlen($item_params['title']) > 25)
			{
				$title_parts = explode(' ', $item_params['title']);
				$item_params['title'] = '';
				
				foreach ($title_parts as $title_part_id => $title_part)
				{
					if (strlen($item_params['title'] . ' ' . $title_part) < 25) $item_params['title'] .= ' ' . $title_part;
					else break;
				}
			}
			if ( trim($item_params['title']) != '' ) echo $item_params['title'] . '<br/>';
		}           
?>