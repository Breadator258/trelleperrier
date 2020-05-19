-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           5.7.24 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Listage de la structure de la base pour 2phpd
CREATE DATABASE IF NOT EXISTS `2phpd` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci */;
USE `2phpd`;

-- Listage de la structure de la table 2phpd. accounts_infos
CREATE TABLE IF NOT EXISTS `accounts_infos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(25) NOT NULL DEFAULT '0',
  `email` char(255) NOT NULL DEFAULT '0',
  `password` char(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table 2phpd. lists_tasks
CREATE TABLE IF NOT EXISTS `lists_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL DEFAULT '0',
  `list_name` char(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '0',
  `task_name` char(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '0',
  `finished` char(3) COLLATE utf8_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table 2phpd. todo_lists_friends
CREATE TABLE IF NOT EXISTS `todo_lists_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_name` char(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '0',
  `user_friend` char(25) COLLATE utf8_swedish_ci NOT NULL DEFAULT '0',
  `can_write` char(3) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'no',
  `viewed_notif` char(3) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table 2phpd. todo_lists_list
CREATE TABLE IF NOT EXISTS `todo_lists_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(25) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `list_name` char(70) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `shared` char(3) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table 2phpd. users_friends
CREATE TABLE IF NOT EXISTS `users_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(25) COLLATE utf8_swedish_ci NOT NULL DEFAULT '0',
  `friend_username` char(25) COLLATE utf8_swedish_ci NOT NULL DEFAULT '0',
  `request_accepted` char(3) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- Les données exportées n'étaient pas sélectionnées.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
