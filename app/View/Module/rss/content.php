<?php
    /**
     * @var core\View $this
     * @var array $items
     */

$rss = '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
<channel>
<title>' . $this->app->title . '</title>
<link xmlns:xi="http://www.w3.org/2001/XInclude">' . $this->app->url . '</link>
<description xmlns:xi="http://www.w3.org/2001/XInclude">' . $this->app->title . '</description>
<lastBuildDate>' . date('D, d M Y H:i:s O') . '</lastBuildDate>';

    if ($items) {
        foreach ($items as $item_id => $item_params) {
            $rss .= '<item>';
            $rss .= '<title><![CDATA[';
            if (isset($item_params['title'])) $rss .= h($item_params['title']);
            $rss .= ']]></title>';
            $rss .= '<link>' . $this->app->url . '/module/item/' . $item_params['id'] . '</link>';
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