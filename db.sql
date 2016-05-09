-- Adminer 3.1.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `midi` /*!40100 DEFAULT CHARACTER SET cp1251 */;
USE `midi`;

DROP TABLE IF EXISTS `module_cats`;
CREATE TABLE `module_cats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `rate` int(10) unsigned NOT NULL DEFAULT '350',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `module_cats` (`id`, `title`, `access`, `rate`) VALUES
(1,	'Рубрика',	1,	350);

DROP TABLE IF EXISTS `module_comments`;
CREATE TABLE `module_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` int(10) unsigned NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user` int(10) unsigned NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `access` tinyint(1) NOT NULL DEFAULT '1',
  `rate` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `module_items`;
CREATE TABLE `module_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `price` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `icq` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `skype` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `occupation` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user` int(10) unsigned NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `rate` int(10) unsigned NOT NULL DEFAULT '350',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 2013-07-05 10:52:15
