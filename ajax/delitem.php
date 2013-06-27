<?php
	//initialization
	require_once('../inc/init.inc');	
	require_once('../' . assign('module') . '/init.inc');
	
	$id = mysql_real_escape_string(assign('id'));
	
	if ($id != '')
	{
		//image
		$item_info = sql_get_row('SELECT * FROM ' . MODULE . ' WHERE id = ' . $id . ';');
		if ($item_info)
		{
			if ( isset($item_info) && $item_info['image'] != '')
			{
				$image = $item_info['image'];
				unlink(MODULE_IMAGE_PATH . $image);
				unlink(MODULE_IMAGE_PATH . 's_' . $image);
			}		
			sql_execute('DELETE FROM `' . MODULE . '` WHERE id = ' . $id . ';');
		}
	}
	echo true;
?>