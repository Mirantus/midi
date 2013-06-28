<?php
require('../core/init.php');
require('./init.php');

if (isset($config['items'])) {
	$type = isset($config['cats']) ? 'cats' : 'items';
	require('inc/' . $type . '.php');
	$site->pagePath = $site->modulePath . '/' . $type;
} else {
	$items = parse_ini_file($site->moduleItemsPath . '/items.ini', true);
	if ($items) {
		$pager_info = $site->getPager($items, 10);
		$items = $pager_info['items'];
		$pager = $pager_info['pager'];
	}
}

include($site->layoutPath . '/default.phtml');