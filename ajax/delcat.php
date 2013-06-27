<?php
	//initialization
	require_once('../inc/init.inc');	
	require_once('../' . assign('module') . '/init.inc');
	
	$cat = mysql_real_escape_string(assign('cat'));
	
	if ($cat != '' && sql_get_row('SELECT * FROM ' . MODULE . '_cats WHERE id = ' . $cat . ';') )
	{
		$items_info = sql_get_row('SELECT * FROM ' . MODULE . ' WHERE cat = ' . $cat . ';');			
		if ($items_info)
		{
			foreach($items_info as $item_id => $item_params)
			{
				if ( $item_params['image'] != '')
				{
					unlink(MODULE_IMAGE_PATH . $item_params['image']);
					unlink(MODULE_IMAGE_PATH . 's_' . $item_params['image']);
				}		
				sql_execute('DELETE FROM `' . MODULE . '` WHERE id = ' . $item_params['id'] . ';');
			}
		}
		
		sql_execute('DELETE FROM ' . MODULE . '_cats WHERE id = ' . $cat . ';');
	}
	echo true;
?>