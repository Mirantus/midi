<?php
$config = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config.ini', true);
if (isset($config['debug_db'])) {
    $db = mysql_connect($config['debug_db']['host'], $config['debug_db']['user'], $config['debug_db']['password']);
    mysql_query('CREATE DATABASE IF NOT EXISTS ' . $config['debug_db']['dbname'], $db);
    mysql_close($db);
}

require('core/init.php');

//for every module
foreach ($site->config['modules'] as $module => $module_access) {
    $module_config = parse_ini_file($site->path . '/' . $module . '/config.ini', true);

    foreach(array('comments', 'items', 'cats')  as $entity) {
        if (!isset($module_config[$entity])) continue;
        $sql_entity = 'CREATE TABLE IF NOT EXISTS `' . $module . '_' . $entity . '` (';
        $entity_fields = array();

        foreach ($module_config[$entity] as $field => $value) {
            switch ($field) {
                case 'id':
                    $entity_fields[] = '`' . $field . '` int(10) unsigned NOT NULL auto_increment';
                    break;
                case 'item':
                    $entity_fields[] = '`' . $field . '` int(10) unsigned NOT NULL default \'' . $value . '\'';
                    break;
                case 'cat':
                    $entity_fields[] = '`' . $field . '` int(10) unsigned NOT NULL default \'' . $value . '\'';
                    break;
                case 'text':
                    $entity_fields[] = '`' . $field . '` text collate utf8_unicode_ci NOT NULL default \'' . $value . '\'';
                    break;
                case 'price':
                    $entity_fields[] = '`' . $field . '` int(10) unsigned NOT NULL default \'' . $value . '\'';
                    break;
                case 'image':
                    $entity_fields[] = '`' . $field . '` varchar(255) collate utf8_unicode_ci NOT NULL default \'' . $value . '\'';
                    break;
                case 'file':
                    $entity_fields[] = '`' . $field . '` varchar(255) collate utf8_unicode_ci NOT NULL default \'' . $value . '\'';
                    break;
                case 'icq':
                    $entity_fields[] = '`' . $field . '` int(10) unsigned NOT NULL default \'' . $value . '\'';
                    break;
                case 'url':
                    $entity_fields[] = '`' . $field . '` varchar(255) collate utf8_unicode_ci NOT NULL default \'' . $value . '\'';
                    break;
                case 'email':
                    $entity_fields[] = '`' . $field . '` varchar(255) collate utf8_unicode_ci NOT NULL default \'' . $value . '\'';
                    break;
                case 'user':
                    $entity_fields[] = '`' . $field . '` int(10) unsigned NOT NULL';
                    break;
                case 'ip':
                    $entity_fields[] = '`' . $field . '` varchar(15) collate utf8_unicode_ci NOT NULL default \'0\'';
                    break;
                case 'date':
                    $entity_fields[] = '`' . $field . '` date NOT NULL default \'0000-00-00\'';
                    break;
                case 'access':
                    $entity_fields[] = '`' . $field . '` tinyint(1) unsigned NOT NULL default \'' . $value . '\'';
                    break;
                case 'rate':
                    $entity_fields[] = '`' . $field . '` int(10) unsigned NOT NULL default \'' . $value . '\'';
                    break;
                default:
                    $entity_fields[] = '`' . $field . '` varchar(255) collate utf8_unicode_ci NOT NULL default \'' . $value . '\'';
            }
        }

        $sql_entity .= implode($entity_fields, ',') . ',PRIMARY KEY  (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';

        $site->db->exec($sql_entity);
    }


    if ( !file_exists($site->path . '/' . $module) ) mkdir($site->path . '/' . $module);
    if ( !file_exists($site->path . '/' . $module . '/items') ) mkdir($site->path . '/' . $module . '/items');
    if (isset($module_config['items'])) {
        if ( !file_exists($site->path . '/' . $module . '/items/i') ) mkdir($site->path . '/' . $module . '/items/i');
        if ( !file_exists($site->path . '/' . $module . '/items/i/thumbs') ) mkdir($site->path . '/' . $module . '/items/i/thumbs');
    } else {
        if ( !file_exists($site->path . '/' . $module . '/items/content') ) mkdir($site->path . '/' . $module . '/items/content');
    }

    if ( file_exists($site->path . '/' . $module . '/' . 'setup.php') ) include($site->path . '/' . $module . '/' . 'setup.php');
}

echo 'done';