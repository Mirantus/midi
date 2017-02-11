-- Adminer 3.1.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `midi` /*!40100 DEFAULT CHARACTER SET cp1251 */;
USE `midi`;

DROP TABLE IF EXISTS `module_cat`;
CREATE TABLE `module_cat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sort` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `module_cats` (`id`, `title`, `access`, `sort`) VALUES
(1,	'Рубрика',	1,	1);

DROP TABLE IF EXISTS `module_comment`;
CREATE TABLE `module_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` int(10) unsigned NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user` int(10) unsigned NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `access` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `module_item`;
CREATE TABLE `module_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `price` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user` int(10) unsigned NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sort` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `email`, `password`, `role`, `name`, `ip`, `date`) VALUES
  (1,	'admin@localhost',	'$1$T19otBvn$28hD1rKulzsKfdSciMUQQ1',	'admin',	'Admin',	'',	'0000-00-00');

-- 2013-07-05 10:52:15
