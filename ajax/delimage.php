<?php
	//initialization
	require_once('../inc/init.inc');	
	require_once('../' . assign('module') . '/init.inc');
	
	$id = mysql_real_escape_string(assign('id'));
	
	if ($id != '')
	{
		$item_info = sql_get_row('SELECT image FROM ' . MODULE . ' WHERE id = ' . $id . ';');
		if ($item_info && $item_info['image'] != '')
		{
			$image = $item_info['image'];
			sql_execute('UPDATE `' . MODULE . '` SET image = "" WHERE id = ' . $id . ';');
			unlink(MODULE_IMAGE_PATH . $image);
			unlink(MODULE_IMAGE_PATH . 's_' . $image);
		}		
	}
	echo true;
?>