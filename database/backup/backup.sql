-- MySQL dump 10.13  Distrib 8.3.0, for macos14.2 (x86_64)
--
-- Host: 127.0.0.1    Database: rifle
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '父级权限',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (27,'model.posts.create','web','2024-03-01 14:17:35','2024-03-15 10:52:25','47'),(28,'model.posts.detail','web','2024-03-01 14:17:35','2024-03-15 10:52:19','47'),(29,'model.posts.update','web','2024-03-01 14:17:35','2024-03-15 10:52:11','47'),(30,'model.posts.delete','web','2024-03-01 14:17:35','2024-03-15 10:51:55','47'),(31,'model.posts.list','web','2024-03-01 14:17:35','2024-03-15 10:51:45','47'),(32,'model.users.create','web','2024-03-01 14:17:35','2024-03-15 10:51:38','48'),(33,'model.users.detail','web','2024-03-01 14:17:35','2024-03-15 10:51:30','48'),(34,'model.users.update','web','2024-03-01 14:17:35','2024-03-15 10:51:24','48'),(35,'model.users.delete','web','2024-03-01 14:17:35','2024-03-15 10:51:19','48'),(36,'model.users.list','web','2024-03-01 14:17:35','2024-03-15 10:51:03','48'),(37,'model.roles.create','web','2024-03-01 14:17:35','2024-03-15 10:50:56','49'),(38,'model.roles.detail','web','2024-03-01 14:17:35','2024-03-15 10:50:52','49'),(39,'model.roles.update','web','2024-03-01 14:17:35','2024-03-15 10:50:47','49'),(40,'model.roles.delete','web','2024-03-01 14:17:35','2024-03-15 10:50:42','49'),(41,'model.roles.list','web','2024-03-01 14:17:35','2024-03-15 10:50:38','49'),(42,'model.permissions.create','web','2024-03-01 14:17:35','2024-03-15 10:50:32','50'),(43,'model.permissions.detail','web','2024-03-01 14:17:35','2024-03-15 10:50:27','50'),(44,'model.permissions.update','web','2024-03-01 14:17:35','2024-03-15 10:50:22','50'),(45,'model.permissions.delete','web','2024-03-01 14:17:35','2024-03-15 10:50:16','50'),(46,'model.permissions.list','web','2024-03-01 14:17:35','2024-03-15 10:50:11','50'),(47,'model.posts.*','web','2024-03-01 14:31:31','2024-03-01 14:31:31',NULL),(48,'model.users.*','web','2024-03-01 14:31:31','2024-03-01 14:31:31',NULL),(49,'model.roles.*','web','2024-03-01 14:31:31','2024-03-01 14:31:31',NULL),(50,'model.permissions.*','web','2024-03-01 14:31:31','2024-03-01 14:31:31',NULL),(51,'model.api_manages.create','web','2024-03-15 10:25:30','2024-03-15 10:49:32','56'),(52,'model.api_manages.detail','web','2024-03-15 10:25:30','2024-03-15 10:49:26','56'),(53,'model.api_manages.update','web','2024-03-15 10:25:30','2024-03-15 10:49:20','56'),(54,'model.api_manages.delete','web','2024-03-15 10:25:30','2024-03-15 10:49:15','56'),(55,'model.api_manages.list','web','2024-03-15 10:25:30','2024-03-15 10:49:08','56'),(56,'model.api_manages.*','web','2024-03-15 10:25:30','2024-03-15 10:25:30',NULL),(57,'model.menus.create','web','2024-03-15 10:25:30','2024-03-15 10:48:21','62'),(58,'model.menus.detail','web','2024-03-15 10:25:31','2024-03-15 10:48:15','62'),(59,'model.menus.update','web','2024-03-15 10:25:31','2024-03-15 10:47:29','62'),(60,'model.menus.delete','web','2024-03-15 10:25:31','2024-03-15 10:47:16','62'),(61,'model.menus.list','web','2024-03-15 10:25:31','2024-03-15 10:47:08','62'),(62,'model.menus.*','web','2024-03-15 10:25:31','2024-03-15 10:25:31',NULL),(63,'model.dicts.create','web','2024-03-15 10:25:31','2024-03-15 10:44:51','68'),(64,'model.dicts.detail','web','2024-03-15 10:25:31','2024-03-15 10:41:38','68'),(65,'model.dicts.update','web','2024-03-15 10:25:31','2024-03-15 10:41:33','68'),(66,'model.dicts.delete','web','2024-03-15 10:25:31','2024-03-15 10:41:28','68'),(67,'model.dicts.list','web','2024-03-15 10:25:31','2024-03-15 10:41:23','68'),(68,'model.dicts.*','web','2024-03-15 10:25:31','2024-03-15 10:25:31',NULL),(69,'model.dict_groups.create','web','2024-03-15 10:25:31','2024-03-15 10:41:09','74'),(70,'model.dict_groups.detail','web','2024-03-15 10:25:31','2024-03-15 10:41:05','74'),(71,'model.dict_groups.update','web','2024-03-15 10:25:31','2024-03-15 10:41:01','74'),(72,'model.dict_groups.delete','web','2024-03-15 10:25:31','2024-03-15 10:40:57','74'),(73,'model.dict_groups.list','web','2024-03-15 10:25:31','2024-03-15 10:40:53','74'),(74,'model.dict_groups.*','web','2024-03-15 10:25:31','2024-03-15 10:25:31',NULL),(75,'model.post_categories.create','web','2024-03-15 10:25:31','2024-03-15 10:39:05','80'),(76,'model.post_categories.detail','web','2024-03-15 10:25:31','2024-03-15 10:39:01','80'),(77,'model.post_categories.update','web','2024-03-15 10:25:31','2024-03-15 10:38:57','80'),(78,'model.post_categories.delete','web','2024-03-15 10:25:31','2024-03-15 10:38:43','80'),(79,'model.post_categories.list','web','2024-03-15 10:25:31','2024-03-15 10:38:50','80'),(80,'model.post_categories.*','web','2024-03-15 10:25:31','2024-03-15 10:25:31',NULL),(81,'model.post_tags.create','web','2024-03-15 10:25:31','2024-03-15 10:37:48','86'),(82,'model.post_tags.detail','web','2024-03-15 10:25:31','2024-03-15 10:37:44','86'),(83,'model.post_tags.update','web','2024-03-15 10:25:31','2024-03-15 10:37:39','86'),(84,'model.post_tags.delete','web','2024-03-15 10:25:31','2024-03-15 10:37:35','86'),(85,'model.post_tags.list','web','2024-03-15 10:25:31','2024-03-15 10:36:42','86'),(86,'model.post_tags.*','web','2024-03-15 10:25:31','2024-03-15 10:25:31',NULL),(87,'model.post_comments.*','web','2024-03-15 10:44:34','2024-03-15 10:44:34',NULL),(88,'model.post_comments.create','web','2024-03-15 10:44:34','2024-03-15 10:44:34','87'),(89,'model.post_comments.detail','web','2024-03-15 10:44:34','2024-03-15 10:44:34','87'),(90,'model.post_comments.update','web','2024-03-15 10:44:34','2024-03-15 10:44:34','87'),(91,'model.post_comments.delete','web','2024-03-15 10:44:34','2024-03-15 10:44:34','87'),(92,'model.post_comments.list','web','2024-03-15 10:44:34','2024-03-15 10:44:34','87');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-15 12:27:12
