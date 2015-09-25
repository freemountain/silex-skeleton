-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `author` (`id`, `first_name`, `last_name`) VALUES
(1,	'Stephen',	'King'),
(2,	'J.K.',	'Rowling'),
(3,	'Dan',	'Brown'),
(4,	'Cornelia',	'Cornelia'),
(5,	'Friedrich',	'Schiller');

DROP TABLE IF EXISTS `book`;
CREATE TABLE `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `isbn` varchar(24) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `book_fi_35872e` (`publisher_id`),
  KEY `book_fi_ea464c` (`author_id`),
  CONSTRAINT `book_fk_35872e` FOREIGN KEY (`publisher_id`) REFERENCES `publisher` (`id`),
  CONSTRAINT `book_fk_ea464c` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `book` (`id`, `title`, `isbn`, `publisher_id`, `author_id`) VALUES
(1,	'Die Bibel',	'123456',	1,	4),
(2,	'Das Kapital',	'$â‚¬@',	2,	3);

DROP TABLE IF EXISTS `publisher`;
CREATE TABLE `publisher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `publisher` (`id`, `name`) VALUES
(1,	'Springer'),
(2,	'Reclam'),
(3,	'G+J');

-- 2015-09-25 18:04:16
