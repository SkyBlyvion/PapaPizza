-- MySQL dump 10.13  Distrib 5.7.29, for Linux (x86_64)
--
-- Host: localhost    Database: papapizza
-- ------------------------------------------------------
-- Server version	5.7.29

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES UTF8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `is_allergic` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredient`
--

LOCK TABLES `ingredient` WRITE;
/*!40000 ALTER TABLE `ingredient` DISABLE KEYS */;
INSERT INTO `ingredient` VALUES (1,'tomate','base',0,1),(2,'crème fraiche','base',1,1),(3,'mozzarella','fromage',0,1),(4,'emmental','fromage',0,1),(5,'chèvre','fromage',0,1),(6,'roquefort','fromage',1,1),(7,'parmesan','fromage',0,1),(8,'jambon','viande',0,1),(9,'lardons','viande',0,1),(10,'poulet','viande',0,1),(11,'merguez','viande',0,1),(12,'chorizo','viande',0,1),(13,'saucisse','viande',0,1),(14,'thon','poisson',0,1),(15,'saumon','poisson',0,1),(16,'anchois','poisson',1,1),(17,'olives','légume',0,1),(18,'champignons','légume',0,1),(19,'oignons','légume',0,1),(20,'poivrons','légume',0,1),(21,'artichauts','légume',0,1),(22,'aubergines','légume',0,1),(23,'courgettes','légume',0,1),(24,'pommes de terre','légume',0,1),(25,'oeuf','viande',0,1),(26,'câpres','autre',0,1),(27,'miel','autre',0,1),(28,'persil','autre',0,1),(29,'basilic','autre',0,1),(30,'origan','autre',0,1),(31,'piment','autre',0,1),(32,'huile piquante','autre',0,1);
/*!40000 ALTER TABLE `ingredient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `date_order` datetime NOT NULL,
  `date_delivered` datetime DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'En cours',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_row`
--

DROP TABLE IF EXISTS `order_row`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_row` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) NOT NULL,
  `price` float NOT NULL,
  `order_id` int(11) NOT NULL,
  `pizza_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `pizza_id` (`pizza_id`),
  CONSTRAINT `order_row_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  CONSTRAINT `order_row_ibfk_2` FOREIGN KEY (`pizza_id`) REFERENCES `pizza` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_row`
--

LOCK TABLES `order_row` WRITE;
/*!40000 ALTER TABLE `order_row` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_row` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pizza`
--

DROP TABLE IF EXISTS `pizza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pizza` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pizza_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pizza`
--

LOCK TABLES `pizza` WRITE;
/*!40000 ALTER TABLE `pizza` DISABLE KEYS */;
INSERT INTO `pizza` VALUES (1,'Margarita','margarita.jpg',1,1),(2,'4 fromages','4fromages.jpg',1,1),(3,'Reine','reine.jpg',1,1),(4,'Royale','royale.jpg',1,1),(5,'Calzone','calzone.jpg',1,1),(6,'Hawaienne','hawaienne.jpg',1,1),(7,'Chorizo','chorizo.jpg',1,1),(8,'Poulet','poulet.jpg',1,1),(9,'Saumon','saumon.jpg',1,1),(10,'Végétarienne','vegetarienne.jpg',1,1),(11,'Paysanne','paysanne.jpg',1,1),(12,'Orientale','orientale.jpg',1,1),(13,'Océane','oceane.jpg',1,1),(14,'Pizzaiolo','pizzaiolo.jpg',1,1),(21,'El Fuego','65797c2d29049_El_Fuego.png',1,1),(22,'testtt','657c0ee616e7c_Paysanne.png',1,1),(23,'aaaa','657c254421af2_El_Fuego.png',0,1),(24,'zzzzz','657c25eb32a4f_Paysanne.png',0,1),(25,'aaa','657c2784df8d8_Paysanne.png',0,1),(26,'ddzq','657c2831867fe_El_Fuego.png',0,1),(27,'user1','657c2d938f7af_Paysanne.png',1,18),(28,'testuser1oadmin','657c301682e07_El_Fuego.png',1,18),(29,'3emepizzauser','657c307252160_Paysanne.png',1,18),(30,'pizzauser4','657c34406292f_Paysanne.png',1,18),(31,'pizzaAdmin1','657c4c20d90a9_Paysanne.png',0,1),(32,'pizzaadmin','657c4fc76d490_El_Fuego.png',0,1),(33,'pizzaUser5','657c525d6a5f5_El_Fuego.png',1,18),(34,'aaa','657c557c53bf8_Paysanne.png',0,1);
/*!40000 ALTER TABLE `pizza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pizza_ingredient`
--

DROP TABLE IF EXISTS `pizza_ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pizza_ingredient` (
  `pizza_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  KEY `pizza_id` (`pizza_id`),
  KEY `ingredient_id` (`ingredient_id`),
  KEY `unit_id` (`unit_id`),
  CONSTRAINT `pizza_ingredient_ibfk_1` FOREIGN KEY (`pizza_id`) REFERENCES `pizza` (`id`),
  CONSTRAINT `pizza_ingredient_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient` (`id`),
  CONSTRAINT `pizza_ingredient_ibfk_3` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pizza_ingredient`
--

LOCK TABLES `pizza_ingredient` WRITE;
/*!40000 ALTER TABLE `pizza_ingredient` DISABLE KEYS */;
INSERT INTO `pizza_ingredient` VALUES (1,1,5,1),(1,3,5,1),(1,4,5,1),(2,1,5,1),(2,3,5,1),(2,4,5,1),(2,5,5,1),(3,1,5,1),(3,3,5,1),(3,4,5,1),(3,7,5,1),(4,1,5,1),(4,3,5,1),(4,4,5,1),(4,8,5,1),(5,1,5,1),(5,3,5,1),(5,4,5,1),(5,9,5,1),(6,1,5,1),(6,3,5,1),(6,4,5,1),(6,10,5,1),(6,11,5,1),(7,1,5,1),(7,3,5,1),(7,4,5,1),(7,12,5,1),(7,13,5,1),(8,1,5,1),(8,3,5,1),(8,4,5,1),(8,14,5,1),(8,15,5,1),(9,1,5,1),(9,3,5,1),(9,4,5,1),(9,16,5,1),(9,17,5,1),(10,1,5,1),(10,3,5,1),(10,4,5,1),(10,18,5,1),(10,19,5,1),(11,1,5,1),(11,3,5,1),(11,4,5,1),(11,20,5,1),(11,21,5,1),(12,1,5,1),(12,3,5,1),(12,4,5,1),(12,22,5,1),(12,23,5,1),(13,1,5,1),(13,3,5,1),(13,4,5,1),(13,24,5,1),(13,25,5,1),(14,1,5,1),(14,3,5,1),(14,4,5,1),(14,26,5,1),(14,27,5,1),(21,1,5,1),(21,4,5,1),(21,10,5,1),(21,11,5,1),(21,12,5,1),(21,17,5,1),(21,18,5,1),(21,29,5,1),(21,31,5,1),(22,1,5,1),(22,8,5,1),(22,9,5,1),(23,1,5,1),(23,3,5,1),(23,5,5,1),(23,8,5,1),(23,10,5,1),(23,11,5,1),(23,17,5,1),(23,18,5,1),(24,17,5,1),(24,18,5,1),(24,24,5,1),(25,2,5,1),(25,8,5,1),(25,16,5,1),(26,1,5,1),(26,8,5,1),(26,16,5,1),(27,1,5,1),(27,9,5,1),(27,10,5,1),(28,2,5,1),(28,3,5,1),(28,7,5,1),(28,8,5,1),(28,13,5,1),(29,3,5,1),(29,4,5,1),(29,11,5,1),(29,13,5,1),(30,13,5,1),(31,2,5,1),(31,3,5,1),(31,10,5,1),(31,11,5,1),(31,12,5,1),(32,1,5,1),(33,4,5,1),(33,12,5,1),(33,13,5,1),(33,21,5,1),(33,29,5,1),(34,1,5,1);
/*!40000 ALTER TABLE `pizza_ingredient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `price`
--

DROP TABLE IF EXISTS `price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` float NOT NULL,
  `size_id` int(11) NOT NULL,
  `pizza_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `size_id` (`size_id`),
  KEY `pizza_id` (`pizza_id`),
  CONSTRAINT `price_ibfk_1` FOREIGN KEY (`size_id`) REFERENCES `size` (`id`),
  CONSTRAINT `price_ibfk_2` FOREIGN KEY (`pizza_id`) REFERENCES `pizza` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `price`
--

LOCK TABLES `price` WRITE;
/*!40000 ALTER TABLE `price` DISABLE KEYS */;
INSERT INTO `price` VALUES (1,5.5,1,1),(2,7.5,2,1),(3,9.5,3,1),(4,6.5,1,2),(5,8.5,2,2),(6,10.5,3,2),(7,7.5,1,3),(8,9.5,2,3),(9,11.5,3,3),(10,8.5,1,4),(11,10.5,2,4),(12,12.5,3,4),(13,8.5,1,5),(14,10.5,2,5),(15,12.5,3,5),(16,9.5,1,6),(17,11.5,2,6),(18,13.5,3,6),(19,9.5,1,7),(20,11.5,2,7),(21,13.5,3,7),(22,9.5,1,8),(23,11.5,2,8),(24,13.5,3,8),(25,9.5,1,9),(26,11.5,2,9),(27,13.5,3,9),(28,9.5,1,10),(29,11.5,2,10),(30,13.5,3,10),(31,9.5,1,11),(32,11.5,2,11),(33,13.5,3,11),(34,9.5,1,12),(35,11.5,2,12),(36,13.5,3,12),(37,9.5,1,13),(38,11.5,2,13),(39,13.5,3,13),(40,9.5,1,14),(41,11.5,2,14),(42,13.5,3,14),(61,12.5,1,21),(62,13.5,2,21),(63,14.5,3,21),(64,1,1,22),(65,2,2,22),(66,3,3,22),(67,5,1,23),(68,5,2,23),(69,5,3,23),(70,1,1,24),(71,1,2,24),(72,1,3,24),(73,2,1,25),(74,2,2,25),(75,2,3,25),(76,4,1,26),(77,4,2,26),(78,4,3,26),(79,5,1,27),(80,5,2,27),(81,5,3,27),(82,5,1,28),(83,5,2,28),(84,5,3,28),(85,4,1,29),(86,5,2,29),(87,6,3,29),(88,1,1,30),(89,2,2,30),(90,3,3,30),(91,4,1,31),(92,4,2,31),(93,4,3,31),(94,1,1,32),(95,1,2,32),(96,1,3,32),(97,5,1,33),(98,5,2,33),(99,5,3,33),(100,1,1,34),(101,1,2,34),(102,1,3,34);
/*!40000 ALTER TABLE `price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `size`
--

DROP TABLE IF EXISTS `size`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `size` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `size`
--

LOCK TABLES `size` WRITE;
/*!40000 ALTER TABLE `size` DISABLE KEYS */;
INSERT INTO `size` VALUES (1,'small (24cm)'),(2,'medium (32cm)'),(3,'large (40cm)');
/*!40000 ALTER TABLE `size` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit`
--

DROP TABLE IF EXISTS `unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit`
--

LOCK TABLES `unit` WRITE;
/*!40000 ALTER TABLE `unit` DISABLE KEYS */;
INSERT INTO `unit` VALUES (1,'g'),(2,'ml'),(3,'cl'),(4,'l'),(5,'unité'),(6,'pincée');
/*!40000 ALTER TABLE `unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin@admin.com','$2y$10$rRhfup7ml0WKMEIYAJ10WOq/V7sVgGxo4sYt5S5ZiNytaW6SnGqKW','admin1','admin20','3 rue de la pizza','66000','Perpignan','France','0663609467',1,1),(18,'doe@doe.com','$2y$10$mOtEUB9DP.UZGAv57ATgPukf65mAQWPh5c1QQpJ9DV3Lxd3rllRvO','doe','de folie',NULL,NULL,NULL,NULL,'01234567890',0,1),(19,'richtofen6@gmail.com','$2y$10$tEdk0skH145iPcXASZUwpOca3IT.AkpiZV4pdNDXcKnVnP6/wXqbG','doe','doe',NULL,NULL,NULL,NULL,'0663609467',1,1),(20,'richtofen60@gmail.com','$2y$10$DmTT2BYMCu0gFGHdC/GPeupAtSG/mYWpWHBcMEw/MWrgH.YD8LBhe','aaa','Lopez',NULL,NULL,NULL,NULL,'0663609467',1,1),(21,'doe2@gmail.com','$2y$10$yAGwlhB5mpd2Gv1gu46A0uIHXHS47vbSfvladDcb6G7r6meWvuhWu','doe','doe',NULL,NULL,NULL,NULL,'0663613497',0,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-15 15:35:52
