/*
SQLyog Ultimate v9.63 
MySQL - 5.0.90 : Database - st
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `dle_statement` */

DROP TABLE IF EXISTS `dle_statement`;

CREATE TABLE `dle_statement` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `teaser` mediumtext NOT NULL,
  `text` mediumtext NOT NULL,
  `user_id` int(10) unsigned NOT NULL default '0',
  `username` varchar(110) NOT NULL default '',
  `email` varchar(110) NOT NULL default '',
  `answer` mediumtext,
  `answer_id` int(10) unsigned NOT NULL default '0',
  `answer_name` varchar(110) NOT NULL default '',
  `type` enum('question','idea','error','thank') NOT NULL default 'idea',
  `category` varchar(50) NOT NULL,
  `status` enum('waiting','working','scheduled','canceled','performed') NOT NULL default 'waiting',
  `plus_count` smallint(5) NOT NULL default '0',
  `minus_count` smallint(5) NOT NULL default '0',
  `comm_num` smallint(5) unsigned NOT NULL default '0',
  `date` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `type` (`type`),
  KEY `plus_minua_count` (`plus_count`,`minus_count`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=cp1251;

/*Table structure for table `dle_statement_comm` */

DROP TABLE IF EXISTS `dle_statement_comm`;

CREATE TABLE `dle_statement_comm` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `statement_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL default '0',
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL default '',
  `text` mediumtext NOT NULL,
  `date` int(10) unsigned NOT NULL default '0',
  `ip_address` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `statement_id_idx` (`statement_id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=cp1251;

/*Table structure for table `dle_statement_log` */

DROP TABLE IF EXISTS `dle_statement_log`;

CREATE TABLE `dle_statement_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `statement_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `ip_address` varchar(15) NOT NULL default '',
  `type` enum('1','-1') NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `statement_id_idx` (`statement_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=cp1251;

/*Table structure for table `dle_statement_subscribers` */

DROP TABLE IF EXISTS `dle_statement_subscribers`;

CREATE TABLE `dle_statement_subscribers` (
  `statement_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL default '0',
  `username` varchar(110) default '',
  `email` varchar(110) default '',
  KEY `st_id_user_id_idx` (`statement_id`,`user_id`),
  KEY `st_id_email_idx` (`statement_id`,`email`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
