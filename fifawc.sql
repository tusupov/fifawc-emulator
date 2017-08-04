-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `team_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`),
  CONSTRAINT `group_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `matches`;
CREATE TABLE `matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `team_1` int(11) NOT NULL,
  `team_2` int(11) NOT NULL,
  `score_1` int(11) NOT NULL,
  `score_2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team_2` (`team_2`),
  KEY `team_1` (`team_1`),
  CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`team_1`) REFERENCES `team` (`id`) ON DELETE CASCADE,
  CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`team_2`) REFERENCES `team` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `games` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `missed` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `team` (`id`, `name`, `games`, `score`, `missed`) VALUES
(1,	'Бразилия',	104,	221,	102),
(2,	'Германия / ФРГ',	106,	224,	121),
(3,	'Италия',	83,	128,	77),
(4,	'Аргентина',	77,	131,	84),
(5,	'Англия',	62,	79,	56),
(6,	'Испания',	59,	92,	66),
(7,	'Франция',	59,	106,	71),
(8,	'Голландия',	50,	86,	48),
(9,	'Уругвай',	51,	80,	71),
(10,	'Швеция',	46,	74,	69),
(11,	'Россия / СССР',	40,	66,	47),
(12,	'Сербия / Югославия',	43,	64,	59),
(13,	'Мексика',	53,	57,	92),
(14,	'Бельгия',	41,	52,	66),
(15,	'Польша',	31,	44,	40),
(16,	'Венгрия',	32,	87,	57),
(17,	'Португалия',	26,	43,	29),
(18,	'Чехия / Чехословакия',	33,	47,	49),
(19,	'Чили',	33,	40,	49),
(20,	'Австрия',	29,	43,	47),
(21,	'Швейцария',	33,	45,	59),
(22,	'Парагвай',	27,	30,	38),
(23,	'США',	33,	37,	62),
(24,	'Румыния',	21,	30,	32),
(25,	'Южная Корея',	31,	31,	67),
(26,	'Дания',	16,	27,	24),
(27,	'Хорватия',	16,	21,	17),
(28,	'Колумбия',	18,	26,	27),
(29,	'Шотландия',	23,	25,	41),
(30,	'Камерун',	23,	18,	43),
(31,	'Коста-Рика',	15,	17,	23),
(32,	'Болгария',	26,	22,	53);

-- 2017-08-04 10:27:31
