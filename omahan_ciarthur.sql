-- MySQL dump 10.13  Distrib 5.7.26, for Linux (x86_64)
--
-- Host: localhost    Database: omahan_ciarthur
-- ------------------------------------------------------
-- Server version	5.7.26

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
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
INSERT INTO `ci_sessions` VALUES ('cbbc020ae5eb872a7f088e0046b8deb661ce0de8','172.31.0.1',1627211524,_binary '__ci_last_regenerate|i:1627211524;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('56f10d17bde56c45eb2772d00f85c2d7331a81f8','172.31.0.1',1627211832,_binary '__ci_last_regenerate|i:1627211832;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('3b263f8ad6e6739a7c7538c33af34d80f5f0867b','172.31.0.1',1627212133,_binary '__ci_last_regenerate|i:1627212133;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('ef4e14386797d76aacb95b642aa6acb3411b01db','172.31.0.1',1627211840,_binary '__ci_last_regenerate|i:1627211840;'),('eed5c1d61c4b49137cc89436e3d4a06f01824cf8','172.31.0.1',1627211840,_binary '__ci_last_regenerate|i:1627211840;'),('2161530f00be66b53d36f55dafcfd9718f0cbabb','172.31.0.1',1627212754,_binary '__ci_last_regenerate|i:1627212754;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('07ba624df857515ef0b6736dfeb2e2bd49c67e61','172.31.0.1',1627213064,_binary '__ci_last_regenerate|i:1627213064;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('9a51af7e2aad5a2efbffe017bd774f72bd2971fc','172.31.0.1',1627213385,_binary '__ci_last_regenerate|i:1627213385;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('45b7384e52a248879eeb70b28e6daa1a642550de','172.31.0.1',1627213783,_binary '__ci_last_regenerate|i:1627213783;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('c0b8080e97d6ff756efaad5c919e3a69d99ca55a','172.31.0.1',1627214688,_binary '__ci_last_regenerate|i:1627214688;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('39f1072dec733123107c7099735afab4711012a6','172.31.0.1',1627215078,_binary '__ci_last_regenerate|i:1627215078;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('a52a8899938557bb75904bc7199bf5710f0ce30a','172.31.0.1',1627215434,_binary '__ci_last_regenerate|i:1627215434;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('3cc0fd57a8a3d8b4ecc32a6c3896653169573507','172.31.0.1',1627215768,_binary '__ci_last_regenerate|i:1627215768;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('05b2328177f938f2e23d35fe8b3703f8fa7424aa','172.31.0.1',1627216071,_binary '__ci_last_regenerate|i:1627216071;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('7f8f8e81b3fee35ce749d352d6139172ab18b7e6','172.31.0.1',1627216658,_binary '__ci_last_regenerate|i:1627216658;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('8c41f4d5d66771a6f8dda2e5bf3826e82c9a5702','172.31.0.1',1627217136,_binary '__ci_last_regenerate|i:1627217136;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('af68c2bc87a8c8e1494c15948fe9892400a13c8c','172.31.0.1',1627217685,_binary '__ci_last_regenerate|i:1627217685;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('9505c3d1ea4f73e1d99fff73c3c0c54ebfed4402','172.31.0.1',1627218035,_binary '__ci_last_regenerate|i:1627218035;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('3f700f874c75687ccb4ad24e30040db72be5b1be','172.31.0.1',1627218718,_binary '__ci_last_regenerate|i:1627218718;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('138c9699a76f572a870e5f807d801e52788d36ef','172.31.0.1',1627219083,_binary '__ci_last_regenerate|i:1627219083;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('b484f8bfb674c208621860bb1399d421e6f7c648','172.31.0.1',1627219526,_binary '__ci_last_regenerate|i:1627219526;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('736d96d889c0b6002694730e9e396bcc2f659598','172.31.0.1',1627220075,_binary '__ci_last_regenerate|i:1627220075;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('5f176e5217163de4ed6da07fc96360f50fbd4db8','172.31.0.1',1627220386,_binary '__ci_last_regenerate|i:1627220386;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('949160b830172ba0de97126ee53210e99134e4b7','172.31.0.1',1627220755,_binary '__ci_last_regenerate|i:1627220755;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('d5d4c517af658e33a6d873021b13dbf0e0d2e493','172.31.0.1',1627221217,_binary '__ci_last_regenerate|i:1627221217;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('49bf169aaf641c9e83729b0865257422ad21617c','172.31.0.1',1627221606,_binary '__ci_last_regenerate|i:1627221606;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('39d12b420d685010be0e9091b349dfaf481e1f86','172.31.0.1',1627221917,_binary '__ci_last_regenerate|i:1627221917;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('909426917781f832b6ca40d305f5fb61fa5901e3','172.31.0.1',1627222265,_binary '__ci_last_regenerate|i:1627222265;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('3844025aa9fc13cea8fa7f3098717df743ea693a','172.31.0.1',1627222625,_binary '__ci_last_regenerate|i:1627222625;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('909dbf33c3d2b000292ea9f7575bfa5e9796b824','172.31.0.1',1627223182,_binary '__ci_last_regenerate|i:1627223182;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('4015ebb97fcd34faf08a1486ba990fea621ef943','172.31.0.1',1627222737,_binary '__ci_last_regenerate|i:1627222737;'),('312758c23701003d9d974bbbe175defd315b8e6d','172.31.0.1',1627223521,_binary '__ci_last_regenerate|i:1627223521;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('6f07a865725387d07eac3e958330dbd341a7a295','172.31.0.1',1627223985,_binary '__ci_last_regenerate|i:1627223985;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('0a741b2d4890e39df02ba58e6c0133c3945fdb0d','172.31.0.1',1627224359,_binary '__ci_last_regenerate|i:1627224359;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('2e52b340f8a2e6b5f3974c5144514d70ddf56103','172.31.0.1',1627224001,_binary '__ci_last_regenerate|i:1627224001;'),('d9bf791e0ca31606c131623029a47351a2d46c6d','172.31.0.1',1627224685,_binary '__ci_last_regenerate|i:1627224685;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('71674c748e920b98393556c8d40c1a020f7e2219','172.31.0.1',1627225148,_binary '__ci_last_regenerate|i:1627225148;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('7be75347b72a979c11d317b0cc0c5c5efa62ba97','172.31.0.1',1627225457,_binary '__ci_last_regenerate|i:1627225457;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";csrfkey|s:8:\"fV4eZxBS\";__ci_vars|a:2:{s:7:\"csrfkey\";s:3:\"new\";s:9:\"csrfvalue\";s:3:\"new\";}csrfvalue|s:20:\"XPyjgKNR3BrxHwvGTi72\";'),('efaca95a3b027d06bb860ff9642f792e80d2468a','172.31.0.1',1627225162,_binary '__ci_last_regenerate|i:1627225162;'),('05251ded8953e89331d1728d4de9ef0b6807c436','172.31.0.1',1627225760,_binary '__ci_last_regenerate|i:1627225760;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";'),('5fd2a73d247121473d9a81e12a089647735d737a','172.31.0.1',1627225824,_binary '__ci_last_regenerate|i:1627225760;identity|s:13:\"administrator\";username|s:13:\"administrator\";email|s:15:\"admin@admin.com\";user_id|s:1:\"1\";old_last_login|s:10:\"1627208336\";last_check|i:1627210291;ion_auth_session_hash|s:40:\"6583d6c4f205998ecacc9f51b68a2a2e44ea0006\";');
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fakultas`
--

DROP TABLE IF EXISTS `fakultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fakultas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `is_active` enum('0','1') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fakultas`
--

LOCK TABLES `fakultas` WRITE;
/*!40000 ALTER TABLE `fakultas` DISABLE KEYS */;
INSERT INTO `fakultas` VALUES (1,'SENI RUPA DAN DESAIN','1',NULL,NULL,NULL,NULL,NULL,NULL),(2,'ILMU SOSIAL DAN POLITIK','1',NULL,NULL,NULL,NULL,NULL,NULL),(3,'HUKUM','1',NULL,NULL,NULL,NULL,NULL,NULL),(4,'EKONOMI DAN BISNIS','1',NULL,NULL,NULL,NULL,NULL,NULL),(5,'KEDOKTERAN','1',NULL,NULL,NULL,NULL,NULL,NULL),(6,'PERTANIAN','1',NULL,NULL,NULL,NULL,NULL,NULL),(7,'TEKNIK','1',NULL,NULL,NULL,NULL,NULL,NULL),(8,'KEGURUAN DAN ILMU PENDIDIKAN','1',NULL,NULL,NULL,NULL,NULL,NULL),(9,'MATEMATIKA DAN ILMU PENGETAHUAN ALAM','1',NULL,NULL,NULL,NULL,NULL,NULL),(10,'ILMU BUDAYA','1',NULL,NULL,NULL,NULL,NULL,NULL),(11,'KEOLAHRAGAAN','1',NULL,NULL,NULL,NULL,NULL,NULL),(12,'VOKASI','1',NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `fakultas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'admin','Administrator'),(2,'members','General User');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mahasiswa` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nim` varchar(100) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `foto_thumb` varchar(100) DEFAULT NULL,
  `angkatan` varchar(5) DEFAULT NULL,
  `prodi_id` varchar(100) DEFAULT NULL,
  `fakultas_id` varchar(100) DEFAULT NULL,
  `is_active` enum('0','1') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mahasiswa`
--

LOCK TABLES `mahasiswa` WRITE;
/*!40000 ALTER TABLE `mahasiswa` DISABLE KEYS */;
INSERT INTO `mahasiswa` VALUES (5,'M3119001','Adam Arthur Faizal','15ba5d5a5c915c8b35be4f04ac26bef7.png','15ba5d5a5c915c8b35be4f04ac26bef7_thumb.png','2019','138','9','1','2021-07-23 15:16:14',1,NULL,NULL,NULL,NULL),(6,'M3119000','Mbah Putih','6c1eeb201aa7a8afa13408e0bfe49d64.jpg',NULL,'2019','134','9','0','2021-07-23 15:24:36',1,'2021-07-25 12:37:16',1,'2021-07-25 12:56:05',NULL),(7,'M3119000','Mbah Putih','70122e4a7b8c329f9aaada648dad8d56.jpg','70122e4a7b8c329f9aaada648dad8d56_thumb.jpg','2019','138','9','1','2021-07-25 12:59:11',1,'2021-07-25 14:00:59',1,NULL,NULL);
/*!40000 ALTER TABLE `mahasiswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prodi`
--

DROP TABLE IF EXISTS `prodi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prodi` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `fakultas_id` varchar(100) DEFAULT NULL,
  `is_active` enum('0','1') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=481 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prodi`
--

LOCK TABLES `prodi` WRITE;
/*!40000 ALTER TABLE `prodi` DISABLE KEYS */;
INSERT INTO `prodi` VALUES (12,'S-1 Seni Rupa Murni','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(13,'S-1 Desain Komunikasi Visual','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(14,'S-1 Desain Interior','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,'S-1 Kriya Seni','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(134,'S-1 Matematika','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(135,'S-1 Fisika','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(136,'S-1 Kimia','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(137,'S-1 Biologi','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(138,'S-1 Informatika','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(141,'S-1 Farmasi','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(172,'S-2 Biosains','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(173,'S-2 Ilmu Fisika','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(178,'S-2 Seni Rupa','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(354,'S-1 Statistika','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(382,'S-2 Kimia','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(476,'S-1 Ilmu Lingkungan','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(479,'S-3 Biologi','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(480,'S-3 Fisika','9',NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `prodi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_email` (`email`),
  UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  UNIQUE KEY `uc_remember_selector` (`remember_selector`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'127.0.0.1','administrator','$2y$10$j1F3WRHYq3c9tjNH1phsd.4Je2GO2qbN1Lxu4VPYk2JuZN6Y6j0E6','admin@admin.com',NULL,'',NULL,NULL,NULL,NULL,NULL,1268889823,1627210291,1,'Admin','istrator','ADMIN','0'),(2,'172.31.0.1','operator','$2y$10$JbSTw29EIs5mnXbdDj.E/Oimc/2BxoAEQkVpaGpHlb5XWRVDxWh8K','operator@email.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1626873123,NULL,1,'operator','operator','operator','081234567890');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_groups`
--

LOCK TABLES `users_groups` WRITE;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;
INSERT INTO `users_groups` VALUES (1,1,1),(2,1,2),(4,2,2);
/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-07-25 15:36:06
