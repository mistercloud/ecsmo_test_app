/*
SQLyog Professional v10.42 
MySQL - 5.5.35-log : Database - ecsmo_test
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cinemas` */

DROP TABLE IF EXISTS `cinemas`;

CREATE TABLE `cinemas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT 'Название',
  `name_eng` varchar(100) DEFAULT NULL COMMENT 'Транслитерация названия',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `cinemas` */

insert  into `cinemas`(`id`,`name`,`name_eng`) values (1,'Аквилон','akvilon'),(2,'Авалон','avalon'),(3,'3D MAX','3d_max');

/*Table structure for table `films` */

DROP TABLE IF EXISTS `films`;

CREATE TABLE `films` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Название фильма',
  `name_eng` varchar(100) NOT NULL COMMENT 'Транслитерация названия фильма',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `films` */

insert  into `films`(`id`,`name`,`name_eng`) values (1,'Фильм 1','film_1'),(2,'Фильм 2','film_2'),(3,'Фильм 3','film_3');

/*Table structure for table `hall_places` */

DROP TABLE IF EXISTS `hall_places`;

CREATE TABLE `hall_places` (
  `id` int(10) unsigned NOT NULL COMMENT 'номер места',
  `cinema_id` int(10) unsigned NOT NULL COMMENT 'id кинотеатра',
  `hall_id` int(10) unsigned NOT NULL COMMENT 'id зала',
  PRIMARY KEY (`id`,`cinema_id`,`hall_id`),
  KEY `fk_hall_places_cinema_id_hall_id` (`cinema_id`,`hall_id`),
  KEY `fk_hall_places_hall_id_cinema_id` (`hall_id`,`cinema_id`),
  CONSTRAINT `fk_hall_places_hall_id_cinema_id` FOREIGN KEY (`hall_id`, `cinema_id`) REFERENCES `halls` (`id`, `cinema_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `hall_places` */

insert  into `hall_places`(`id`,`cinema_id`,`hall_id`) values (1,1,1),(2,1,1),(3,1,1),(4,1,1),(5,1,1),(6,1,1),(1,1,2),(2,1,2),(3,1,2),(4,1,2),(5,1,2),(6,1,2),(1,1,3),(2,1,3),(3,1,3),(4,1,3),(5,1,3),(6,1,3),(1,2,1),(2,2,1),(3,2,1),(4,2,1),(5,2,1),(6,2,1),(1,2,4),(2,2,4),(3,2,4),(4,2,4),(5,2,4),(6,2,4),(1,2,5),(2,2,5),(3,2,5),(4,2,5),(1,3,2),(2,3,2),(3,3,2),(4,3,2),(1,3,6),(2,3,6),(3,3,6),(4,3,6),(1,3,7),(2,3,7),(3,3,7),(4,3,7);

/*Table structure for table `halls` */

DROP TABLE IF EXISTS `halls`;

CREATE TABLE `halls` (
  `id` int(10) unsigned NOT NULL COMMENT 'номер зала',
  `cinema_id` int(10) unsigned NOT NULL COMMENT 'id кинотеатра',
  PRIMARY KEY (`id`,`cinema_id`),
  KEY `fk_halls_cinema_id` (`cinema_id`),
  CONSTRAINT `fk_halls_cinema_id` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `halls` */

insert  into `halls`(`id`,`cinema_id`) values (1,1),(2,1),(3,1),(1,2),(4,2),(5,2),(2,3),(6,3),(7,3);

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id сеанса',
  `cinema_id` int(10) unsigned DEFAULT NULL COMMENT 'id кинотеатра',
  `hall_id` int(10) unsigned DEFAULT NULL COMMENT 'id зала',
  `film_id` int(10) unsigned DEFAULT NULL COMMENT 'id фильма',
  `start_at` datetime DEFAULT NULL COMMENT 'дата начала',
  PRIMARY KEY (`id`),
  KEY `fk_sessions_cinema_id_hall_id` (`cinema_id`,`hall_id`),
  KEY `fk_sessions_film_id` (`film_id`),
  CONSTRAINT `fk_sessions_cinema_id_hall_id` FOREIGN KEY (`cinema_id`, `hall_id`) REFERENCES `halls` (`id`, `cinema_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sessions_film_id` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`cinema_id`,`hall_id`,`film_id`,`start_at`) values (1,1,1,1,'2014-05-23 23:04:33'),(2,1,2,2,'2014-05-23 17:04:49');

/*Table structure for table `ticket_places` */

DROP TABLE IF EXISTS `ticket_places`;

CREATE TABLE `ticket_places` (
  `ticket_id` int(10) unsigned NOT NULL COMMENT 'id билета',
  `hall_place_id` int(10) unsigned NOT NULL COMMENT 'id места',
  `cinema_id` int(10) unsigned NOT NULL COMMENT 'id кинотеатра',
  `hall_id` int(10) unsigned NOT NULL COMMENT 'id зала',
  PRIMARY KEY (`ticket_id`,`hall_place_id`,`cinema_id`,`hall_id`),
  KEY `fk_ticket_places_hall_place_id_hall_id_cinema_id` (`hall_place_id`,`cinema_id`,`hall_id`),
  CONSTRAINT `fk_ticket_places_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ticket_places_hall_place_id_hall_id_cinema_id` FOREIGN KEY (`hall_place_id`, `cinema_id`, `hall_id`) REFERENCES `hall_places` (`id`, `cinema_id`, `hall_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ticket_places` */

insert  into `ticket_places`(`ticket_id`,`hall_place_id`,`cinema_id`,`hall_id`) values (1,1,1,1);

/*Table structure for table `tickets` */

DROP TABLE IF EXISTS `tickets`;

CREATE TABLE `tickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id билета',
  `session_id` int(10) unsigned NOT NULL COMMENT 'id сессии',
  `code` varchar(10) DEFAULT NULL COMMENT 'код',
  `created_at` datetime DEFAULT NULL COMMENT 'время покупки билета',
  PRIMARY KEY (`id`),
  KEY `fk_tickets_session_id` (`session_id`),
  CONSTRAINT `fk_tickets_session_id` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `tickets` */

insert  into `tickets`(`id`,`session_id`,`code`,`created_at`) values (1,1,'1','2014-05-22 18:18:49');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
