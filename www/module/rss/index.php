<?php
require('../../../core/init.php');
require('../init.php');

if (isset($config['items'])) {
    $items = $site->db->query(
        'SELECT * FROM ' . $site->module . '_items ORDER BY id DESC LIMIT 0,10;'
    )->fetchAll(PDO::FETCH_ASSOC);
} else {
    $items = parse_ini_file($site->moduleItemsPath . '/items.ini', true);
    $items = array_slice($items, -10, 10);
}

$rss = '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
 <channel>
<title>' . $site->title . '</title>
<link xmlns:xi="http://www.w3.org/2001/XInclude">' . $site->url . '</link>
<description xmlns:xi="http://www.w3.org/2001/XInclude">' . $site->title . '</description>
<lastBuildDate>' . date('D, d M Y H:i:s O') . '</lastBuildDate>';

if ($items) {
    foreach ($items as $item_id => $item_params) {
        $rss .= '<item>';
        $rss .= '<title><![CDATA[';
        if (isset($item_params['title'])) $rss .= htmlspecialchars($item_params['title'], ENT_QUOTES);
        $rss .= ']]></title>';
        $rss .= '<link>' . $site->moduleUrl . '/item/' . $item_params['id'] . '</link>';
        $rss .= '<description><![CDATA[';
        $rss .= $item_params['text'];
        $rss .= ']]></description>';
        $rss .= '<pubDate>';
        $rss .= date('D, d M Y H:i:s O', strtotime($item_params['date']));
        $rss .= '</pubDate>';
        $rss .= '<pubDateUT>';
        $rss .= strtotime($item_params['date']);
        $rss .= '</pubDateUT>';
        $rss .= '<guid>' . $item_params['id'] . '</guid>';
        $rss .= '</item>';
    }
}

$rss .= '</channel>
</rss>';

echo $rss;