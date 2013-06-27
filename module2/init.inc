<?php
	define ("MODULE", "module2");
	define ("MODULE_TITLE", "Модуль2");
	define ("MODULE_URL", SITE_URL . '/' . MODULE);	
	
	//sql
	require_once(SITE_PATH . '/php/sql.php');
	
	//file	
	define ("MODULE_DATA_PATH", SITE_PATH . '/data/' . MODULE);
	require_once(SITE_PATH . '/php/fs.php');
	
	//image
	define ("MODULE_IMAGE_PATH", MODULE_DATA_PATH . '/images/');
	require_once(SITE_PATH . '/php/image.php');
?>