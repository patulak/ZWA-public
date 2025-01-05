-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: website
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cards` (
  `card_id` int NOT NULL AUTO_INCREMENT,
  `datetime_card` datetime DEFAULT CURRENT_TIMESTAMP,
  `img_path` varchar(255) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `text_card` text,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
INSERT INTO `cards` VALUES (1,'2024-10-21 02:20:32','/sources/stoly.jpg','Ukázka podzemí','Starý štolový systém, který se zde nachází již od roku 1897.'),(2,'2024-10-21 02:20:30','/sources/VA4.jpg','Velká Amerika','Přírodou pohlcený lom který je perlou celého našeho okolí.');
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery` (
  `gallery_id` int NOT NULL AUTO_INCREMENT,
  `description_gal` text,
  `title` varchar(100) DEFAULT NULL,
  `datetime_gal` datetime DEFAULT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
INSERT INTO `gallery` VALUES (1,'Největší lom z našeho systému. V sedmdesátých letech oblíbené pro trempy. Dnes neodolatelné místo pro filmaře, potápěče a všechny ostatní návštěvníky.','Velká Amerika','2024-10-21 02:20:32'),(2,'Oficiálním názvem Trestanecký lom. Za Druhé světové války fungoval jako trestanecký pracovní tábor.','Mexiko','2024-10-21 02:20:32'),(3,'krásný to lom','Malá Amerika',NULL);
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guide`
--

DROP TABLE IF EXISTS `guide`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guide` (
  `tour_id` int NOT NULL,
  `guide_username` varchar(50) NOT NULL,
  PRIMARY KEY (`tour_id`,`guide_username`),
  KEY `guide_username_idx` (`guide_username`),
  CONSTRAINT `guide_username_key` FOREIGN KEY (`guide_username`) REFERENCES `logins` (`username`),
  CONSTRAINT `tour_id_key` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guide`
--

LOCK TABLES `guide` WRITE;
/*!40000 ALTER TABLE `guide` DISABLE KEYS */;
/*!40000 ALTER TABLE `guide` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `img_paths`
--

DROP TABLE IF EXISTS `img_paths`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `img_paths` (
  `gallery_id` int NOT NULL,
  `path_to_img` varchar(255) NOT NULL,
  PRIMARY KEY (`path_to_img`,`gallery_id`),
  KEY `gallery_id_idx` (`gallery_id`),
  CONSTRAINT `gallery_id_key` FOREIGN KEY (`gallery_id`) REFERENCES `gallery` (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `img_paths`
--

LOCK TABLES `img_paths` WRITE;
/*!40000 ALTER TABLE `img_paths` DISABLE KEYS */;
INSERT INTO `img_paths` VALUES (1,'/sources/VA0.jpg'),(1,'/sources/VA1.jpg'),(1,'/sources/VA2.jpg'),(2,'/sources/Mexiko_(lom).jpg'),(2,'/sources/Trestanecky-lom-00.jpg'),(2,'/sources/Trestanecky-lom-01.jpg'),(3,'/sources/uploads/img_67312d1e29bc05.65460206.jpg');
/*!40000 ALTER TABLE `img_paths` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logins`
--

DROP TABLE IF EXISTS `logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logins` (
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(100) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `role` enum('guide','admin','moderator') DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logins`
--

LOCK TABLES `logins` WRITE;
/*!40000 ALTER TABLE `logins` DISABLE KEYS */;
INSERT INTO `logins` VALUES ('koli','$2y$10$iErM8yZkACJ7uJRhk7w05OYC4c/qC/hmn6rK/qP7.sN4JbnoOKtQm','Patrik Kolář','admin'),('mod','$2y$10$OV9cU0fWtYaVMly7Mrjv5.29bUXqtqhD7Q6CjGULUHkDHrUS3CtVC','Mod User','moderator');
/*!40000 ALTER TABLE `logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservations` (
  `reservation_id` int NOT NULL AUTO_INCREMENT,
  `number_of_people` int unsigned DEFAULT NULL,
  `reservation_datetime` datetime DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelded') DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `confirmed_at` datetime DEFAULT NULL,
  `tour_id` int DEFAULT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `tour_id_key_idx` (`tour_id`),
  CONSTRAINT `res_tour_id_key` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tours`
--

DROP TABLE IF EXISTS `tours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tours` (
  `tour_id` int NOT NULL AUTO_INCREMENT,
  `tour_datetime` datetime DEFAULT NULL,
  `capacity` int unsigned DEFAULT NULL,
  `state` enum('open','closed','canceled') DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `payment_status` enum('paid','not_paid') DEFAULT NULL,
  `payment_method` enum('invoice','cash') DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `number_of_guides` int unsigned DEFAULT NULL,
  PRIMARY KEY (`tour_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tours`
--

LOCK TABLES `tours` WRITE;
/*!40000 ALTER TABLE `tours` DISABLE KEYS */;
INSERT INTO `tours` VALUES (1,'2025-01-01 09:00:00',22,'open','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 1',2),(2,'2025-01-02 10:30:00',29,'closed','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 2',3),(3,'2025-01-03 14:00:00',27,'canceled','2025-01-03 22:26:24','2024-12-29 20:24:49','paid','invoice','sefesefefef',2),(4,'2024-01-04 08:30:00',36,'open','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 4',1),(5,'2024-01-05 13:45:00',39,'closed','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 5',1),(6,'2024-01-06 12:00:00',42,'open','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 6',3),(7,'2024-01-07 16:15:00',36,'canceled','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 7',1),(8,'2024-01-08 11:00:00',18,'open','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 8',5),(9,'2024-01-09 09:30:00',16,'closed','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 9',5),(10,'2024-01-10 15:45:00',14,'canceled','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 10',4),(11,'2024-01-11 14:00:00',35,'open','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 11',4),(12,'2024-01-12 12:30:00',36,'closed','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 12',1),(13,'2024-01-13 08:00:00',50,'canceled','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 13',2),(14,'2024-01-14 13:00:00',48,'open','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 14',3),(15,'2024-01-15 10:00:00',46,'closed','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 15',5),(16,'2024-01-16 14:30:00',35,'canceled','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 16',3),(17,'2024-01-17 11:15:00',32,'open','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 17',2),(18,'2024-01-18 16:45:00',48,'closed','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 18',4),(19,'2024-01-19 09:00:00',40,'canceled','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 19',4),(20,'2024-01-20 10:30:00',47,'open','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 20',4),(21,'2024-01-21 15:00:00',42,'closed','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 21',5),(22,'2024-01-22 13:30:00',36,'open','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 22',5),(23,'2024-01-23 14:15:00',16,'canceled','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 23',2),(24,'2024-01-24 11:45:00',20,'closed','2024-12-29 20:24:49','2024-12-29 20:24:49','not_paid','cash','Tour description 24',2),(25,'2024-01-25 08:30:00',30,'open','2024-12-29 20:24:49','2024-12-29 20:24:49','paid','invoice','Tour description 25',4),(26,'2025-01-01 09:00:00',16,'open','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 1',4),(27,'2025-01-02 10:30:00',34,'closed','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 2',1),(28,'2025-01-03 14:00:00',11,'canceled','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 3',4),(29,'2025-01-04 08:30:00',22,'open','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 4',3),(30,'2025-01-05 13:45:00',20,'closed','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 5',5),(31,'2025-01-06 12:00:00',11,'open','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 6',2),(32,'2025-01-07 16:15:00',24,'canceled','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 7',5),(33,'2025-01-08 11:00:00',35,'open','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 8',2),(34,'2025-01-09 09:30:00',38,'closed','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 9',3),(35,'2025-01-10 15:45:00',33,'canceled','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 10',2),(36,'2025-01-11 14:00:00',36,'open','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 11',2),(37,'2025-01-12 12:30:00',47,'closed','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 12',3),(38,'2025-01-13 08:00:00',41,'canceled','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 13',2),(39,'2025-01-14 13:00:00',20,'open','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 14',2),(40,'2025-01-15 10:00:00',47,'closed','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 15',3),(41,'2025-01-16 14:30:00',16,'canceled','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 16',1),(42,'2025-01-17 11:15:00',37,'open','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 17',2),(43,'2025-01-18 16:45:00',19,'closed','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 18',3),(44,'2025-01-19 09:00:00',28,'canceled','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 19',5),(45,'2025-01-20 10:30:00',23,'open','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 20',5),(46,'2025-01-21 15:00:00',23,'closed','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 21',1),(47,'2025-01-22 13:30:00',22,'open','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 22',2),(48,'2025-01-23 14:15:00',47,'canceled','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 23',3),(49,'2025-01-24 11:45:00',35,'closed','2024-12-29 20:29:26','2024-12-29 20:29:26','not_paid','cash','Tour description 24',4),(50,'2025-01-25 08:30:00',33,'open','2024-12-29 20:29:26','2024-12-29 20:29:26','paid','invoice','Tour description 25',5),(51,'2025-01-03 17:52:00',4,'open','2025-01-03 17:52:00','2025-01-03 17:53:51','not_paid','invoice','TOIOKWIJNND',4),(52,'2025-01-03 22:29:00',6,'closed','2025-01-03 22:26:40','2025-01-03 22:26:40','not_paid','cash','rftrftzhftzhfthtfh',3);
/*!40000 ALTER TABLE `tours` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-05 16:40:20
