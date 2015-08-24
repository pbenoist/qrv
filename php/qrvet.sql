-- MySQL dump 10.13  Distrib 5.5.20, for Win32 (x86)
--
-- Host: localhost    Database: qrvet
-- ------------------------------------------------------
-- Server version	5.5.20-log

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
-- Table structure for table `em_animal`
--

DROP TABLE IF EXISTS `em_animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `em_animal` (
  `id_animal` int(11) NOT NULL AUTO_INCREMENT,
  `nuserie` char(15) DEFAULT NULL,
  `codepin` char(7) DEFAULT NULL,
  `email_proprio` char(50) DEFAULT NULL,
  `password_proprio` char(50) DEFAULT NULL,
  `nom_proprio` char(50) DEFAULT NULL,
  `prenom_proprio` char(50) DEFAULT NULL,
  `tel_proprio` char(20) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `nom_animal` char(20) DEFAULT NULL,
  `UID` char(20) DEFAULT NULL,
  `datCreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `code_postal` char(10) DEFAULT NULL,
  `cpt_modif_img` int(11) DEFAULT '0',
  `espece` int(11) DEFAULT '0',
  `race` char(30) DEFAULT NULL,
  `date_naissance` char(15) DEFAULT NULL,
  `rotate_image` int(11) DEFAULT '0',
  `keepLogin` int(11) DEFAULT '0',
  PRIMARY KEY (`id_animal`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `em_animal`
--

LOCK TABLES `em_animal` WRITE;
/*!40000 ALTER TABLE `em_animal` DISABLE KEYS */;
INSERT INTO `em_animal` VALUES (1,'256012345678902','AZER001','pbenoist@emergensoft.fr','aze','BENOIST','Philippe','0676645504',1,'Gaïag',NULL,'2014-08-05 16:27:46','33170',51,0,'','',0,0),(2,'256012345678902','AZER002','','qsd','BENOIST','Fanny','06 27 15 67 67',0,'TOUKY touk',NULL,'2014-08-05 16:27:46',NULL,6,0,NULL,NULL,0,0),(3,'256012345678903','AZER003','','wxc','BASSIER','Joël','06 26 98 33 40',0,'ITSY',NULL,'2014-08-05 16:27:46',NULL,0,0,NULL,NULL,0,0),(4,'256012345678904','AZER004','','rty','HAMMI','Sandrine','06 82 47 55 65',0,'GAUZOR',NULL,'2014-08-05 16:27:46',NULL,0,0,NULL,NULL,0,0),(5,'256012345678905','AZER005','','fgh','COSTE','Jean','06 60 96 75 05',0,'TOTOB',NULL,'2014-08-05 16:27:46',NULL,0,0,NULL,NULL,0,0),(78,'','azer308','pbenoist@emergensoft.fr','aze','BENOISRT','Phil','132132',0,'medor','66d7acb3-31f8-11e4-8','2014-09-01 16:52:48','123123',0,0,'','',0,0),(79,'','azer100','pbenoist@emergensoft.fr','aze','BENOIS','Phil','0505',0,'medor 100','98c4d11d-31f8-11e4-8','2014-09-01 16:54:11','12123',0,0,'','',0,0),(80,'','azer101','','','','','',0,'','f56db66b-31f8-11e4-8','2014-09-01 16:56:47','',0,0,'','',0,0),(81,'','azer102','pbenoist@emergensoft.fr','aze','NOM 1','','',0,'Enr 1','746cb555-31f9-11e4-8','2014-09-01 17:00:20','',0,0,'','',0,0),(82,'','azer105','pbenoist@emergensoft.fr','aze','BENOIST 2','','',0,'from irefox','095b9805-31fa-11e4-8','2014-09-01 17:04:30','',0,0,'','',0,0),(83,'','','','','','','',0,'','439eface-3275-11e4-8','2014-09-02 07:46:35','',6,0,'','',0,0),(84,'','azer010','pbenoist@emergensoft.fr','aze','BENOIST','Philippe','11122233',1,'AZR010','38c55a21-329b-11e4-8','2014-09-02 12:18:17','1312',1,0,'','',0,1),(85,'250268712205129','ky29zk8','pbenoist@emergensoft.fr','sandre33','AZER','Qsd','',0,'aze','a62719df-329b-11e4-8','2014-09-02 12:21:21','3310',0,0,'','',0,0),(88,'','azer020','pbenoist@emergensoft.fr','aze','AZE','','',0,'aze','61864892-329d-11e4-8','2014-09-02 12:33:45','',0,0,'','',0,0),(89,'','azer555','pbenoist@emergensoft.fr','aze','AZE','','',1,'aze','6f500faf-329e-11e4-8','2014-09-02 12:41:17','',0,0,'','',0,1),(90,'','azer666','pbenoist@emergensoft.fr','aze','AZER','','',1,'azer','235f30f0-329f-11e4-8','2014-09-02 12:46:20','',0,0,'','',0,0),(91,'','AZER777','','','','','',0,'','4df7fc3e-32a4-11e4-8','2014-09-02 13:23:18','',0,0,'','',0,0),(92,'','azer888','pbenoist@emergensoft.fr','aze','BEN','Phiol','',1,'azer888','2336599a-32a6-11e4-8','2014-09-02 13:36:26','',1,0,'','',0,0),(93,'250999','azer999','','','','','',0,'','8e60e6ec-32a7-11e4-8','2014-09-02 13:46:35','',0,0,'','',0,0),(95,'123456','azer997','','','','','',0,'','da9f51c7-32a7-11e4-8','2014-09-02 13:48:43','',0,0,'','',0,0),(97,'250QSD','azer993','','','','','',0,'','32521b3a-32a8-11e4-8','2014-09-02 13:51:10','',0,0,'','',0,0),(102,'aze','azer415','pbenoist@emergensoft.fr','aze','COUCOU','Phh','kkjl',1,'azer415','92dc3764-32b3-11e4-8','2014-09-02 15:12:37','jkl',2,0,'','',0,0);
/*!40000 ALTER TABLE `em_animal` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-12 22:43:27
