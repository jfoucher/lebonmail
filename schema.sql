-- Adminer 3.6.1 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `search_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `searches` (
  `email` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(512) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `hash` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `annonces` text CHARACTER SET utf8,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last` text CHARACTER SET utf8 NOT NULL,
  `updated_at` varchar(32) CHARACTER SET utf8 NOT NULL,
  `lang` char(2) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(256) CHARACTER SET utf8 NOT NULL,
  `num_searches` int(11) NOT NULL,
  `end_date` datetime NOT NULL,
  `update_freq` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2013-10-09 09:48:23
