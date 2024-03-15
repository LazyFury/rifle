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
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'2024-03-06 13:50:25','2024-03-06 18:31:36','用户管理',NULL,'user','TableView',8,'/user','ant-design:user-outlined',0,NULL,'menu'),(2,'2024-03-07 10:11:28','2024-03-08 09:44:38','用户列表',NULL,'users','TableView',9,'/users','user',0,1,'menu'),(3,'2024-03-07 10:21:27','2024-03-08 18:13:37','系统设置',NULL,'set','TableView',8,'/set','ant-design:setting-outlined',0,NULL,'menu'),(4,'2024-03-07 15:43:29','2024-03-09 11:09:44','基础设置',NULL,'basic-set','setting/Test',NULL,'/set/basic-set','set',0,3,'menu'),(5,'2024-03-07 18:32:54','2024-03-07 18:34:14','内容管理',NULL,'cms','cms',NULL,'/cms','ant-design:file-text-outlined',0,NULL,'menu'),(6,'2024-03-07 18:33:28','2024-03-11 10:01:30','所有文章',NULL,'articles','TableView',8,'/articles','art',0,5,'menu'),(7,'2024-03-08 13:47:00','2024-03-11 15:28:10','其他配置',NULL,'article-category','TableView',10,'/article-category','arti',0,5,'group'),(8,'2024-03-09 09:37:11','2024-03-11 15:36:40','文章标签',NULL,'article-tags','TableView',11,'/article-tags','tags',0,7,'menu'),(9,'2024-03-11 11:25:50','2024-03-11 18:02:27','字典管理',NULL,'dict','TableView',12,'/set/dict','dict',0,3,'menu'),(10,'2024-03-11 11:29:11','2024-03-11 18:02:20','字典组管理',NULL,'dict-group','TableView',13,'/set/dict-group','set',0,3,'menu'),(11,'2024-03-11 14:21:52','2024-03-11 14:22:25','权限管理',NULL,'permission','permission',NULL,'/permission','ant-design:safety-certificate-outlined',0,NULL,'menu'),(12,'2024-03-11 14:23:30','2024-03-11 14:26:27','权限列表',NULL,'permissions','TableView',14,'/perm/permissions','/',0,11,'menu'),(13,'2024-03-11 14:32:57','2024-03-11 14:56:41','角色管理',NULL,'roles','Permission/Role',15,'/perm/roles','role',0,11,'menu'),(14,'2024-03-11 15:03:44','2024-03-11 15:27:46','分类管理',NULL,'article-groups','TableView',10,'/cms/article-groups','/three',0,7,'menu');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-15 13:36:07
