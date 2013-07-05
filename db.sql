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
(1,	'рубрика',	1,	350);

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `module_comments` (`id`, `item`, `text`, `name`, `email`, `user`, `ip`, `date`, `access`, `rate`) VALUES
(1,	1,	'комментарий',	'Русский',	'',	0,	'127.0.0.1',	'2013-02-20',	1,	0),
(2,	1,	'фыва',	'фыва',	'',	0,	'127.0.0.1',	'2013-02-20',	1,	0);

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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `module_items` (`id`, `cat`, `title`, `text`, `price`, `image`, `file`, `name`, `phone`, `icq`, `skype`, `url`, `email`, `city`, `zip`, `address`, `company`, `occupation`, `user`, `ip`, `date`, `access`, `rate`) VALUES
(1,	1,	'Сытый друг2',	'ыфвафа',	'',	'',	'',	'',	'',	'',	'',	'',	'the-ms@ya.ru',	'',	'',	'',	'',	'',	0,	'127.0.0.1',	'2012-12-06',	1,	350),
(19,	1,	'фыва',	'фыва',	'',	'19.jpg',	'',	'',	'',	'',	'',	'',	'the-ms@ya.ru',	'',	'',	'',	'',	'',	0,	'127.0.0.1',	'2013-02-21',	1,	350),
(4,	1,	'Заголовок',	'оуправления, направленная на обеспечение согласования интересов работников и работодателей по вопросам регулирования трудовых отношений и иных непосредственно связанных с ними отношений, входящих в предмет трудового права. Данное определение включает все виды взаимодействия между работниками (их представителями), работодателями (их представителями) и оуправления, направленная на обеспечение согласования интересов работников и работодателей по вопросам регулирования трудовых отношений и иных непосредственно связанных с ними отношений, входящих в предмет трудового права. Данное определение включает все виды взаимодействия между работниками (их представителями), работодателями (их представителями) и оуправления, направленная на обеспечение согласования интересов работников и работодателей по вопросам регулирования трудовых отношений и иных непосредственно связанных с ними отношений, входящих в предмет трудового права. Данное определение включает все виды взаимодействия между работниками (их представителями), работодателями (их представителями) и',	'',	'4.jpg',	'0_STATIC76f10_d5af4937.jpg',	'',	'',	'',	'',	'',	'the-ms@ya.ru',	'',	'',	'',	'',	'',	0,	'127.0.0.1',	'2012-12-06',	1,	350),
(18,	1,	'фыва',	'фыва',	'',	'',	'2008-08-koshki obnimayutsya-001.jpg',	'',	'',	'',	'',	'',	'info@fs8.ru',	'',	'',	'',	'',	'',	0,	'127.0.0.1',	'2013-02-21',	1,	350),
(17,	1,	'авып',	'',	'',	'17.jpg',	'',	'',	'',	'',	'',	'',	'info@fs8.ru',	'',	'',	'',	'',	'',	0,	'127.0.0.1',	'2013-02-21',	1,	350),
(9,	1,	'asdf',	'asdf',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	0,	'127.0.0.1',	'2013-02-20',	1,	350),
(14,	1,	'asdf',	'dsfa',	'',	'',	'',	'',	'',	'',	'',	'',	'info@buketopt.ru',	'',	'',	'',	'',	'',	0,	'127.0.0.1',	'2013-02-20',	1,	350),
(15,	1,	'тест',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'info@fs8.ru',	'',	'',	'',	'',	'',	0,	'127.0.0.1',	'2013-02-21',	1,	350),
(16,	1,	'фывафва',	'фыва',	'',	'',	'',	'',	'',	'',	'',	'',	'info@fs8.ru',	'',	'',	'',	'',	'',	0,	'127.0.0.1',	'2013-02-21',	1,	350),
(20,	1,	'выфа',	'фыва',	'',	'20.jpg',	'',	'',	'',	'',	'',	'',	'the-ms@ya.ru',	'',	'',	'',	'',	'',	0,	'127.0.0.1',	'2013-02-21',	1,	350);

-- 2013-07-05 10:52:15
