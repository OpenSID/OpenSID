-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `user_action`;
CREATE TABLE `user_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_action` (`id`, `name`, `description`) VALUES
(1,	'siteman/auth',	'authentication page'),
(2,	'first/*',	'halaman publik untuk pengguna website desa'),
(3,	'main/index',	''),
(4,	'web/*',	'Administrasi website desa.')
-- (5,	'category/edit',	'Ability to edit categories after they have been created. This includes modifying parent/child relationships of categories.'),
-- (6,	'category/add',	'Ability to create new categories.'),
-- (7,	'category/update',	'Ability to update categories.'),
-- (8,	'something/else',	'Ability to do something')
;

DROP TABLE IF EXISTS `user_grup`;
CREATE TABLE `user_grup` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `action` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_grup` (`id`, `name`, `description`, `action`) VALUES
(0,	'Tamu',	'Untuk pengguna halaman publik website desa\r\n',	'1,2,3'),
(1,	'Administrator',	'Administrator has access to everything',	'*'),
(2,	'Operator',	'Operator OpenSID',	'1'),
(3,	'Redaksi',	'',	'1,2,3,4'),
(4,	'Kontributor',	'',	'1,2,3,4');

-- 2018-05-14 09:04:50
