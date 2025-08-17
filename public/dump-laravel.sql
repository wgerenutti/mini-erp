/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: laravel
-- ------------------------------------------------------
-- Server version	10.11.13-MariaDB-ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cupons`
--

DROP TABLE IF EXISTS `cupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `codigo` varchar(255) NOT NULL,
  `valor_desc` decimal(10,2) DEFAULT NULL,
  `pct_desc` decimal(5,2) DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `minimo` decimal(10,2) NOT NULL,
  `validade` date NOT NULL,
  `valid_to` datetime DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `uso_maximo` int(10) unsigned DEFAULT NULL,
  `uso_count` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cupons_codigo_unique` (`codigo`),
  KEY `cupons_created_by_foreign` (`created_by`),
  KEY `cupons_updated_by_foreign` (`updated_by`),
  CONSTRAINT `cupons_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cupons_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cupons`
--

LOCK TABLES `cupons` WRITE;
/*!40000 ALTER TABLE `cupons` DISABLE KEYS */;
INSERT INTO `cupons` VALUES
(1,1,1,'teste-001',100.00,10.00,NULL,150.00,'2025-08-14',NULL,1,NULL,0,'2025-08-06 23:21:53','2025-08-07 02:29:42');
/*!40000 ALTER TABLE `cupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estoque`
--

DROP TABLE IF EXISTS `estoque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estoque` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `produto_id` bigint(20) unsigned NOT NULL,
  `variacao_id` bigint(20) unsigned DEFAULT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estoque_created_by_foreign` (`created_by`),
  KEY `estoque_updated_by_foreign` (`updated_by`),
  KEY `estoque_produto_id_foreign` (`produto_id`),
  KEY `estoque_variacao_id_foreign` (`variacao_id`),
  CONSTRAINT `estoque_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `estoque_produto_id_foreign` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estoque_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `estoque_variacao_id_foreign` FOREIGN KEY (`variacao_id`) REFERENCES `variacoes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estoque`
--

LOCK TABLES `estoque` WRITE;
/*!40000 ALTER TABLE `estoque` DISABLE KEYS */;
INSERT INTO `estoque` VALUES
(1,NULL,NULL,4,NULL,55,'2025-07-20 23:30:25','2025-08-09 22:38:02'),
(3,NULL,NULL,6,NULL,18,'2025-07-22 00:37:06','2025-08-17 20:45:48'),
(11,NULL,NULL,14,NULL,50,'2025-07-25 22:50:51','2025-07-25 22:50:51'),
(12,NULL,NULL,14,5,50,'2025-07-25 22:50:51','2025-07-25 22:50:51'),
(14,NULL,NULL,14,8,10,'2025-07-26 20:33:05','2025-07-26 20:33:05'),
(15,NULL,NULL,15,NULL,7,'2025-07-29 00:55:25','2025-08-03 23:28:04');
/*!40000 ALTER TABLE `estoque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_07_20_000012_create_produtos_table',2),
(5,'2025_07_20_000542_create_variacoes_table',2),
(6,'2025_07_20_001047_create_estoque_table',2),
(7,'2025_07_20_004017_create_pedidos_table',2),
(8,'2025_07_20_006130_create_pedido_itens_table',2),
(9,'2025_07_20_007210_create_cupons_table',2),
(10,'2025_07_20_009250_create_pedido_cupons_table',2),
(12,'2025_07_31_005311_add_endereco_to_pedidos_table',3),
(13,'2025_08_03_235506_update_cupons_and_pedido_cupons_tables',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_cupons`
--

DROP TABLE IF EXISTS `pedido_cupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_cupons` (
  `pedido_id` bigint(20) unsigned NOT NULL,
  `cupom_id` bigint(20) unsigned NOT NULL,
  `desconto_aplicado` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pedido_id`,`cupom_id`),
  KEY `pedido_cupons_cupom_id_foreign` (`cupom_id`),
  CONSTRAINT `pedido_cupons_cupom_id_foreign` FOREIGN KEY (`cupom_id`) REFERENCES `cupons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedido_cupons_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_cupons`
--

LOCK TABLES `pedido_cupons` WRITE;
/*!40000 ALTER TABLE `pedido_cupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedido_cupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_itens`
--

DROP TABLE IF EXISTS `pedido_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_itens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `produto_id` bigint(20) unsigned NOT NULL,
  `variacao_id` bigint(20) unsigned DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unit` decimal(10,2) NOT NULL,
  `total_item` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_itens_created_by_foreign` (`created_by`),
  KEY `pedido_itens_updated_by_foreign` (`updated_by`),
  KEY `pedido_itens_pedido_id_foreign` (`pedido_id`),
  KEY `pedido_itens_produto_id_foreign` (`produto_id`),
  KEY `pedido_itens_variacao_id_foreign` (`variacao_id`),
  CONSTRAINT `pedido_itens_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedido_itens_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedido_itens_produto_id_foreign` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedido_itens_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedido_itens_variacao_id_foreign` FOREIGN KEY (`variacao_id`) REFERENCES `variacoes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_itens`
--

LOCK TABLES `pedido_itens` WRITE;
/*!40000 ALTER TABLE `pedido_itens` DISABLE KEYS */;
INSERT INTO `pedido_itens` VALUES
(1,NULL,NULL,5,15,NULL,10,10.00,100.00,'2025-07-31 01:21:16','2025-07-31 01:21:16'),
(2,NULL,NULL,5,4,NULL,1,123455.61,123455.61,'2025-07-31 01:21:16','2025-07-31 01:21:16'),
(3,NULL,NULL,6,15,NULL,1,10.00,10.00,'2025-08-03 23:21:11','2025-08-03 23:21:11'),
(4,NULL,NULL,7,15,NULL,2,10.00,20.00,'2025-08-03 23:28:04','2025-08-03 23:28:04'),
(5,NULL,NULL,8,6,NULL,1,848.95,848.95,'2025-08-03 23:28:38','2025-08-03 23:28:38'),
(6,NULL,NULL,9,4,NULL,1,123455.61,123455.61,'2025-08-09 22:38:02','2025-08-09 22:38:02'),
(7,NULL,NULL,10,6,NULL,1,848.95,848.95,'2025-08-17 20:45:48','2025-08-17 20:45:48');
/*!40000 ALTER TABLE `pedido_itens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `frete` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `cep` varchar(255) NOT NULL,
  `endereco` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedidos_created_by_foreign` (`created_by`),
  KEY `pedidos_updated_by_foreign` (`updated_by`),
  CONSTRAINT `pedidos_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedidos_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES
(2,NULL,NULL,123555.61,0.00,123555.61,'pending','86080440','Rua Jesuíno Caetano dos Santos, Jardim Imagawa - Londrina/PR','2025-07-31 01:07:23','2025-07-31 01:07:23'),
(3,NULL,NULL,123555.61,0.00,123555.61,'pending','86080440','Rua Jesuíno Caetano dos Santos, Jardim Imagawa - Londrina/PR','2025-07-31 01:11:44','2025-07-31 01:11:44'),
(5,NULL,NULL,123555.61,0.00,123555.61,'pending','86080440','Rua Jesuíno Caetano dos Santos, Jardim Imagawa - Londrina/PR','2025-07-31 01:21:16','2025-07-31 01:21:16'),
(6,NULL,NULL,10.00,20.00,30.00,'pending','86080440','Rua Jesuíno Caetano dos Santos, Jardim Imagawa - Londrina/PR','2025-08-03 23:21:11','2025-08-03 23:21:11'),
(7,NULL,NULL,20.00,20.00,40.00,'pending','86080440','Rua Jesuíno Caetano dos Santos, Jardim Imagawa - Londrina/PR','2025-08-03 23:28:04','2025-08-03 23:28:04'),
(8,NULL,NULL,848.95,0.00,848.95,'pending','86080000','Rua Tanganica, Ouro Verde - Londrina/PR','2025-08-03 23:28:38','2025-08-03 23:28:38'),
(9,NULL,NULL,123455.61,0.00,123455.61,'pending','86080440','Rua Jesuíno Caetano dos Santos, Jardim Imagawa - Londrina/PR','2025-08-09 22:38:02','2025-08-09 22:38:02'),
(10,NULL,NULL,848.95,0.00,848.95,'pending','86080440','Rua Jesuíno Caetano dos Santos, Jardim Imagawa - Londrina/PR','2025-08-17 20:45:47','2025-08-17 20:45:47');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produtos_created_by_foreign` (`created_by`),
  KEY `produtos_updated_by_foreign` (`updated_by`),
  CONSTRAINT `produtos_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `produtos_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` VALUES
(4,NULL,1,'teste de produto',123455.61,'2025-07-20 23:30:24','2025-07-22 00:31:24'),
(6,1,1,'teste produto 3',848.95,'2025-07-22 00:37:06','2025-07-22 00:37:06'),
(14,1,1,'teste variação',8484.89,'2025-07-25 22:50:51','2025-07-25 22:50:51'),
(15,1,1,'pilha',10.00,'2025-07-29 00:55:25','2025-07-29 00:55:25');
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('e64IDJU49qNnMw19l6QNx3qBl5dSN9KvdcJDIaYN',1,'172.19.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSnlnRXJOQ1RJUzVmemh4UmdSQXcwVExLVWpHTkF4REhPSnBUMXhBQSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3Byb2R1dG9zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo4OiJjYXJyaW5obyI7YTo3OntzOjU6Iml0ZW1zIjthOjE6e3M6NjoiNjpudWxsIjthOjU6e3M6MTA6InByb2R1dG9faWQiO2k6NjtzOjExOiJ2YXJpYWNhb19pZCI7TjtzOjQ6Im5vbWUiO3M6MTU6InRlc3RlIHByb2R1dG8gMyI7czo1OiJwcmVjbyI7czo2OiI4NDguOTUiO3M6MTA6InF1YW50aWRhZGUiO2k6MTt9fXM6ODoic3VidG90YWwiO2Q6ODQ4Ljk1O3M6NToiZnJldGUiO2k6MDtzOjU6InRvdGFsIjtkOjc0OC45NTtzOjM6ImNlcCI7TjtzOjg6ImVuZGVyZWNvIjtOO3M6NToiY3Vwb20iO2E6Mzp7czoyOiJpZCI7aToxO3M6NjoiY29kaWdvIjtzOjk6InRlc3RlLTAwMSI7czoxNzoiZGVzY29udG9fYXBsaWNhZG8iO2Q6MTAwO319fQ==',1754780024),
('wodTrUiRwNYywLzKILUsmZoxDLirR67ynoMq2Ssw',1,'172.19.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWGlrejVSZEFRcDJwNFRHdUNGdGliTm5VeDZmYXR1ak1LZFNRZ21mVyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyOToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3BlZGlkb3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjg6ImNhcnJpbmhvIjthOjY6e3M6NToiaXRlbXMiO2E6MDp7fXM6ODoic3VidG90YWwiO2k6MDtzOjU6ImZyZXRlIjtpOjIwO3M6NToidG90YWwiO2k6MjA7czozOiJjZXAiO047czo4OiJlbmRlcmVjbyI7Tjt9fQ==',1755463575);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'William','williamgerenuttidm@gmail.com',NULL,'$2y$12$zTfpHNzq7Vs9lWqWBErj0uIaXD4B66CkorlTk41H3PQ3v/I92tQNS','FJk4NKrA6W6nnWWRbNYLN7Fmq4FqcbmgmqWii0gi3YzEP1ETklELlL2jS7Gb','2025-07-20 15:30:03','2025-07-20 15:30:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `variacoes`
--

DROP TABLE IF EXISTS `variacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `variacoes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `produto_id` bigint(20) unsigned NOT NULL,
  `nome` varchar(255) NOT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `variacoes_created_by_foreign` (`created_by`),
  KEY `variacoes_updated_by_foreign` (`updated_by`),
  KEY `variacoes_produto_id_foreign` (`produto_id`),
  CONSTRAINT `variacoes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `variacoes_produto_id_foreign` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `variacoes_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variacoes`
--

LOCK TABLES `variacoes` WRITE;
/*!40000 ALTER TABLE `variacoes` DISABLE KEYS */;
INSERT INTO `variacoes` VALUES
(5,NULL,NULL,14,'verde',123.45,'2025-07-25 22:50:51','2025-07-25 22:50:51'),
(8,NULL,NULL,14,'azul',8484.89,'2025-07-26 20:33:05','2025-07-26 20:33:05');
/*!40000 ALTER TABLE `variacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'laravel'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-08-17 20:16:47
