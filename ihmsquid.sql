-- MySQL dump 10.13  Distrib 5.6.26, for Linux (x86_64)
--
-- Host: localhost    Database: ihmsquid
-- ------------------------------------------------------
-- Server version	5.6.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `uf_authorize_group`
--

DROP TABLE IF EXISTS `uf_authorize_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_authorize_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `hook` varchar(200) NOT NULL COMMENT 'A code that references a specific action or URI that the group has access to.',
  `conditions` text NOT NULL COMMENT 'The conditions under which members of this group have access to this hook.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uf_authorize_group`
--

LOCK TABLES `uf_authorize_group` WRITE;
/*!40000 ALTER TABLE `uf_authorize_group` DISABLE KEYS */;
INSERT INTO `uf_authorize_group` VALUES (1,1,'uri_dashboard','always()'),(2,2,'uri_dashboard','always()'),(3,2,'uri_users','always()'),(4,1,'uri_account_settings','always()'),(5,1,'update_account_setting','equals(self.id, user.id)&&in(property,[\"email\",\"locale\",\"password\"])'),(6,2,'update_account_setting','in(property,[\"email\",\"display_name\",\"title\",\"locale\",\"enabled\"])'),(7,2,'view_account_setting','in(property,[\"user_name\",\"email\",\"display_name\",\"title\",\"locale\",\"enabled\",\"groups\",\"primary_group_id\"])'),(8,2,'delete_account','!in_group(user.id,2)'),(9,2,'create_account','always()'),(10,2,'app_admin','always()'),(11,5,'uri_access','always()'),(12,6,'uri_filterconf','always()'),(13,7,'uri_stats','always()');
/*!40000 ALTER TABLE `uf_authorize_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uf_authorize_user`
--

DROP TABLE IF EXISTS `uf_authorize_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_authorize_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hook` varchar(200) NOT NULL COMMENT 'A code that references a specific action or URI that the user has access to.',
  `conditions` text NOT NULL COMMENT 'The conditions under which the user has access to this action.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uf_authorize_user`
--

LOCK TABLES `uf_authorize_user` WRITE;
/*!40000 ALTER TABLE `uf_authorize_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `uf_authorize_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uf_blacklist_categories`
--

DROP TABLE IF EXISTS `uf_blacklist_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_blacklist_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `allowed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uf_blacklist_categories`
--

LOCK TABLES `uf_blacklist_categories` WRITE;
/*!40000 ALTER TABLE `uf_blacklist_categories` DISABLE KEYS */;
INSERT INTO `uf_blacklist_categories` VALUES (1,'adult',0),(2,'agressif',1),(3,'arjel',1),(4,'astrology',1),(5,'audio-video',1),(6,'bank',1),(7,'bitcoin',1),(8,'blog',1),(9,'celebrity',1),(10,'chat',1),(11,'child',1),(12,'cleaning',1),(13,'cooking',1),(14,'dangerous_material',1),(15,'dating',1),(16,'ddos',1),(17,'dialer',1),(18,'download',1),(19,'drogue',1),(20,'educational_games',1),(21,'filehosting',1),(22,'financial',1),(23,'forums',1),(24,'gambling',1),(25,'games',1),(26,'hacking',1),(27,'jobsearch',1),(28,'lingerie',0),(29,'liste',1),(30,'malware',1),(31,'manga',1),(32,'marketingware',1),(33,'mixed_adult',1),(34,'mobile',1),(35,'phishing',1),(36,'press',1),(37,'publicite',1),(38,'radio',1),(39,'reaffected',1),(40,'redirector',1),(41,'remote-control',1),(43,'sect',0),(44,'sexual_education',1),(45,'shopping',1),(46,'shortener',1),(47,'social_networks',1),(48,'sports',1),(49,'strict_redirector',1),(50,'strong_redirector',1),(51,'translation',1),(52,'tricheur',1),(53,'update',1),(54,'warez',1),(55,'webmail',1),(56,'associations_religieuses',1);
/*!40000 ALTER TABLE `uf_blacklist_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uf_blockedsitelog`
--

DROP TABLE IF EXISTS `uf_blockedsitelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_blockedsitelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datelog` datetime DEFAULT NULL,
  `category` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `url` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `id_salle` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_salle` (`id_salle`),
  CONSTRAINT `uf_blockedsitelog_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `uf_salle` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `uf_confgen`
--

DROP TABLE IF EXISTS `uf_confgen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_confgen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `libelle` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `value` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uf_confgen`
--

LOCK TABLES `uf_confgen` WRITE;
/*!40000 ALTER TABLE `uf_confgen` DISABLE KEYS */;
INSERT INTO `uf_confgen` VALUES (1,'ip_squid','192.168.186.129'),(2,'ssh_user','ihmsquid'),(3,'squidguard_conf_path','/etc/squidguard.conf'),(4,'squid_conf_path','/etc/squid.conf'),(5,'delay_pool_restore_rate','11'),(6,'delay_pool_max_size','22'),(7,'squidguard_db','/tmp');
/*!40000 ALTER TABLE `uf_confgen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uf_configuration`
--

DROP TABLE IF EXISTS `uf_configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_configuration` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plugin` varchar(50) NOT NULL COMMENT 'The name of the plugin that manages this setting (set to ''userfrosting'' for core settings)',
  `name` varchar(150) NOT NULL COMMENT 'The name of the setting.',
  `value` longtext NOT NULL COMMENT 'The current value of the setting.',
  `description` text NOT NULL COMMENT 'A brief description of this setting.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='A configuration table, mapping global configuration options to their values.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uf_configuration`
--

LOCK TABLES `uf_configuration` WRITE;
/*!40000 ALTER TABLE `uf_configuration` DISABLE KEYS */;
INSERT INTO `uf_configuration` VALUES (1,'userfrosting','site_title','IHMSQUID','The title of the site.  By default, displayed in the title tag, as well as the upper left corner of every user page.'),(2,'userfrosting','admin_email','admin@userfrosting.com','The administrative email for the site.  Automated emails, such as activation emails and password reset links, will come from this address.'),(3,'userfrosting','email_login','1','Specify whether users can login via email address or username instead of just username.'),(4,'userfrosting','can_register','0','Specify whether public registration of new accounts is enabled.  Enable if you have a service that users can sign up for, disable if you only want accounts to be created by you or an admin.'),(5,'userfrosting','enable_captcha','0','Specify whether new users must complete a captcha code when registering for an account.'),(6,'userfrosting','require_activation','0','Specify whether email activation is required for newly registered accounts.  Accounts created on the admin side never need to be activated.'),(7,'userfrosting','resend_activation_threshold','0','The time, in seconds, that a user must wait before requesting that the activation email be resent.'),(8,'userfrosting','reset_password_timeout','10800','The time, in seconds, before a user\'s password reminder email expires.'),(9,'userfrosting','default_locale','fr_FR','The default language for newly registered users.'),(10,'userfrosting','minify_css','0','Specify whether to use concatenated, minified CSS (production) or raw CSS includes (dev).'),(11,'userfrosting','minify_js','0','Specify whether to use concatenated, minified JS (production) or raw JS includes (dev).'),(12,'userfrosting','version','0.3.0','The current version of UserFrosting.'),(13,'userfrosting','author','Florian Cartron, Eliot Gillet, Laurent Lecomte','The author of the site.  Will be used in the site\'s author meta tag.'),(14,'userfrosting','show_terms_on_register','0','Specify whether or not to show terms and conditions when registering.'),(15,'userfrosting','site_location','','The nation or state in which legal jurisdiction for this site falls.'),(16,'userfrosting','install_status','complete',''),(17,'userfrosting','root_account_config_token','',''),(19,'userfrosting','ext_sites','<li><a href=\"https://www.google.fr\"  target=\"_blank\">Google</a></li>','');
/*!40000 ALTER TABLE `uf_configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uf_custom_conf`
--

DROP TABLE IF EXISTS `uf_custom_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_custom_conf` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uf_custom_conf`
--

LOCK TABLES `uf_custom_conf` WRITE;
/*!40000 ALTER TABLE `uf_custom_conf` DISABLE KEYS */;
INSERT INTO `uf_custom_conf` VALUES (1,'Aucun accès',''),(2,'Accès total','');
/*!40000 ALTER TABLE `uf_custom_conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uf_custom_conf_items`
--

DROP TABLE IF EXISTS `uf_custom_conf_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_custom_conf_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `id_custom_conf` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_custom_conf` (`id_custom_conf`),
  CONSTRAINT `uf_custom_conf_items_ibfk_1` FOREIGN KEY (`id_custom_conf`) REFERENCES `uf_custom_conf` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uf_customblacklist`
--

DROP TABLE IF EXISTS `uf_customblacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_customblacklist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `uf_customblacklist_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `uf_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uf_custombypasslist`
--

DROP TABLE IF EXISTS `uf_custombypasslist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_custombypasslist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `uf_custombypasslist_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `uf_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uf_customwhitelist`
--

DROP TABLE IF EXISTS `uf_customwhitelist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_customwhitelist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `uf_customwhitelist_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `uf_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uf_ext_sites`
--

DROP TABLE IF EXISTS `uf_ext_sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_ext_sites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `url` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `uf_group`
--

DROP TABLE IF EXISTS `uf_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Specifies whether this permission is a default setting for new accounts.',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Specifies whether this permission can be deleted from the control panel.',
  `theme` varchar(100) NOT NULL DEFAULT 'default' COMMENT 'The theme assigned to primary users in this group.',
  `landing_page` varchar(200) NOT NULL DEFAULT 'account' COMMENT 'The page to take primary members to when they first log in.',
  `new_user_title` varchar(200) NOT NULL DEFAULT 'New User' COMMENT 'The default title to assign to new primary users.',
  `icon` varchar(100) NOT NULL DEFAULT 'fa fa-user' COMMENT 'The icon representing primary users in this group.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uf_group`
--

LOCK TABLES `uf_group` WRITE;
/*!40000 ALTER TABLE `uf_group` DISABLE KEYS */;
INSERT INTO `uf_group` VALUES (1,'Utilisateurs du site',1,0,'nyx','access','Utilisateur du site','fa fa-user'),(2,'Administrateur',0,0,'nyx','users','Administrateur du site','fa fa-flag'),(5,'Accès de la salle',2,1,'nyx','access','Formateur','fa fa-user'),(6,'Gestion du filtrage',0,1,'nyx','customfilter','Formateur','fa fa-user'),(7,'Statistiques d\'utilisation',0,1,'nyx','stats_global','Personnel Administratif','fa fa-user');
/*!40000 ALTER TABLE `uf_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uf_group_user`
--

DROP TABLE IF EXISTS `uf_group_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_group_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Maps users to their group(s)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uf_group_user`
--

LOCK TABLES `uf_group_user` WRITE;
/*!40000 ALTER TABLE `uf_group_user` DISABLE KEYS */;
INSERT INTO `uf_group_user` VALUES (1,1,1);
/*!40000 ALTER TABLE `uf_group_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uf_logline`
--

DROP TABLE IF EXISTS `uf_logline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_logline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datelog` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `host_ip` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `cache_result` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `result_code` int(11) DEFAULT NULL,
  `bytes` int(11) DEFAULT NULL,
  `request_method` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `url` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `username` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `access_type` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `ressource_ip` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `object_type` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `uf_salle`
--

DROP TABLE IF EXISTS `uf_salle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_salle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `description` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `network` varchar(15) COLLATE utf8_bin NOT NULL,
  `mask_cidr` int(11) NOT NULL,
  `id_customconf` int(10) unsigned DEFAULT NULL,
  `ip_formateur` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_customconf` (`id_customconf`),
  CONSTRAINT `uf_salle_ibfk_1` FOREIGN KEY (`id_customconf`) REFERENCES `uf_custom_conf` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uf_user`
--

DROP TABLE IF EXISTS `uf_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `activation_token` varchar(225) NOT NULL,
  `last_activation_request` datetime NOT NULL,
  `lost_password_request` tinyint(1) NOT NULL DEFAULT '0',
  `lost_password_timestamp` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(150) NOT NULL,
  `sign_up_stamp` datetime NOT NULL,
  `last_sign_in_stamp` datetime DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Specifies if the account is enabled.  Disabled accounts cannot be logged in to, but they retain all of their data and settings.',
  `primary_group_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Specifies the primary group for the user.',
  `locale` varchar(10) NOT NULL DEFAULT 'en_US' COMMENT 'The language and locale to use for this user.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uf_user`
--

LOCK TABLES `uf_user` WRITE;
/*!40000 ALTER TABLE `uf_user` DISABLE KEYS */;
INSERT INTO `uf_user` VALUES (1,'admin','admin','$2y$10$aRDK/rBvEgJitNuxtvenCuPJcKfIxjmvqsFL8eabFSGi6mIIDR6PK','admin@admin.fr','4bd088cd3f96f75d5eb2d9ee1729652b','2015-09-01 13:45:58',0,NULL,1,'Utilisateur root','2015-09-01 13:45:58','2015-11-28 12:47:04',1,1,'fr_FR');
/*!40000 ALTER TABLE `uf_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uf_workinghours`
--

DROP TABLE IF EXISTS `uf_workinghours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uf_workinghours` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hourstartam` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `hourendam` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `hourstartpm` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `hourendpm` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uf_workinghours`
--

LOCK TABLES `uf_workinghours` WRITE;
/*!40000 ALTER TABLE `uf_workinghours` DISABLE KEYS */;
INSERT INTO `uf_workinghours` VALUES (1,'9:00','12:30','14:00','17:30');
/*!40000 ALTER TABLE `uf_workinghours` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

