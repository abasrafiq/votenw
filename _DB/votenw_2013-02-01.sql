# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.9)
# Datenbank: votenw
# Erstellungsdauer: 2013-02-01 14:08:01 +0100
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle answers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `answers`;

CREATE TABLE `answers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `answers` WRITE;
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;

INSERT INTO `answers` (`id`, `title`)
VALUES
	(1,'der'),
	(2,'die'),
	(3,'das');

/*!40000 ALTER TABLE `answers` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle questions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions`;

CREATE TABLE `questions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` longtext,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;

INSERT INTO `questions` (`id`, `title`, `description`, `slug`)
VALUES
	(1,'API','Application Programming Interface','api'),
	(2,'IDE','Integrated Development Einvironment','ide'),
	(3,'Tag','Im Sinne von z.B. HTML Tag','tag'),
	(4,'Slug','URL Slug','slug'),
	(5,'View','Nach dem MVC Prinzip','view'),
	(6,'Interface','Aus der OOP','interface'),
	(7,'Event','Event aus z.B. jQuery','event'),
	(8,'Test','test','test');

/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle questions_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions_users`;

CREATE TABLE `questions_users` (
  `question_id` int(11) NOT NULL,
  `answers_id` int(11) NOT NULL,
  `user_ip` varchar(15) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `questions_users` WRITE;
/*!40000 ALTER TABLE `questions_users` DISABLE KEYS */;

INSERT INTO `questions_users` (`question_id`, `answers_id`, `user_ip`)
VALUES
	(1,1,'192.168.0.100'),
	(1,2,'255.255.0.0'),
	(1,3,'9.8.7.6'),
	(1,2,'6.6.6.6'),
	(1,2,'1234'),
	(1,2,'42'),
	(6,1,'127.0.0.1'),
	(6,2,'89.7.6.1'),
	(6,2,'109.80.9.1'),
	(1,1,'127.0.0.1'),
	(2,3,'127.0.0.1'),
	(3,1,'127.0.0.1'),
	(4,1,'127.0.0.1'),
	(5,2,'127.0.0.1'),
	(7,1,'127.0.0.1'),
	(8,2,'127.0.0.1');

/*!40000 ALTER TABLE `questions_users` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
