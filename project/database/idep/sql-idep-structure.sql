/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 8.0.30 : Database - idep
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`idep` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `idep`;

/*Table structure for table `activity_log` */

DROP TABLE IF EXISTS `activity_log`;

CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=325 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `audit_logs` */

DROP TABLE IF EXISTS `audit_logs`;

CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `properties` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `host` varchar(46) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1751 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `country` */

DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso1` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso2` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `dusun` */

DROP TABLE IF EXISTS `dusun`;

CREATE TABLE `dusun` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` int DEFAULT '0',
  `desa_id` bigint unsigned NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `coordinates` longtext COLLATE utf8mb4_unicode_ci,
  `kode_pos` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dusun_desa_id_foreign` (`desa_id`),
  CONSTRAINT `dusun_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `kelurahan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `kabupaten` */

DROP TABLE IF EXISTS `kabupaten`;

CREATE TABLE `kabupaten` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kabupaten',
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `path` longtext COLLATE utf8mb4_unicode_ci,
  `coordinates` longtext COLLATE utf8mb4_unicode_ci,
  `aktif` tinyint(1) DEFAULT '0',
  `provinsi_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kabupaten_provinsi_id_foreign` (`provinsi_id`),
  CONSTRAINT `kabupaten_provinsi_id_foreign` FOREIGN KEY (`provinsi_id`) REFERENCES `provinsi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9514 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `kecamatan` */

DROP TABLE IF EXISTS `kecamatan`;

CREATE TABLE `kecamatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `kabupaten_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kecamatan_kabupaten_id_foreign` (`kabupaten_id`),
  CONSTRAINT `kecamatan_kabupaten_id_foreign` FOREIGN KEY (`kabupaten_id`) REFERENCES `kabupaten` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=950837 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `kelurahan` */

DROP TABLE IF EXISTS `kelurahan`;

CREATE TABLE `kelurahan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` int DEFAULT '0',
  `kecamatan_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelurahan_kecamatan_id_foreign` (`kecamatan_id`),
  CONSTRAINT `kelurahan_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9508322006 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `master_jenis_kelompok` */

DROP TABLE IF EXISTS `master_jenis_kelompok`;

CREATE TABLE `master_jenis_kelompok` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `master_jenis_kelompok_nama_unique` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `media` */

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB AUTO_INCREMENT=381 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=236 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mjabatan` */

DROP TABLE IF EXISTS `mjabatan`;

CREATE TABLE `mjabatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mjenisbantuan` */

DROP TABLE IF EXISTS `mjenisbantuan`;

CREATE TABLE `mjenisbantuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mjeniskegiatan` */

DROP TABLE IF EXISTS `mjeniskegiatan`;

CREATE TABLE `mjeniskegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mkaitansdg` */

DROP TABLE IF EXISTS `mkaitansdg`;

CREATE TABLE `mkaitansdg` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mkategorilokasikegiatan` */

DROP TABLE IF EXISTS `mkategorilokasikegiatan`;

CREATE TABLE `mkategorilokasikegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mkelompokmarjinal` */

DROP TABLE IF EXISTS `mkelompokmarjinal`;

CREATE TABLE `mkelompokmarjinal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mkomponenmodel` */

DROP TABLE IF EXISTS `mkomponenmodel`;

CREATE TABLE `mkomponenmodel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mpartner` */

DROP TABLE IF EXISTS `mpartner`;

CREATE TABLE `mpartner` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` longtext COLLATE utf8mb4_unicode_ci,
  `aktif` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mpendonor` */

DROP TABLE IF EXISTS `mpendonor`;

CREATE TABLE `mpendonor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mpendonorkategori_id` bigint unsigned NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pic` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mpendonor_mpendonorkategori_id_foreign` (`mpendonorkategori_id`),
  CONSTRAINT `mpendonor_mpendonorkategori_id_foreign` FOREIGN KEY (`mpendonorkategori_id`) REFERENCES `mpendonorkategori` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mpendonorkategori` */

DROP TABLE IF EXISTS `mpendonorkategori`;

CREATE TABLE `mpendonorkategori` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mperan` */

DROP TABLE IF EXISTS `mperan`;

CREATE TABLE `mperan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `msatuan` */

DROP TABLE IF EXISTS `msatuan`;

CREATE TABLE `msatuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `msektor` */

DROP TABLE IF EXISTS `msektor`;

CREATE TABLE `msektor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mtargetreinstra` */

DROP TABLE IF EXISTS `mtargetreinstra`;

CREATE TABLE `mtargetreinstra` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=551 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `permission_role` */

DROP TABLE IF EXISTS `permission_role`;

CREATE TABLE `permission_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_role_permission_id_foreign` (`permission_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `guard_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT 'web',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_nama_guard_name_unique` (`nama`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `provinsi` */

DROP TABLE IF EXISTS `provinsi`;

CREATE TABLE `provinsi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `negara_id` bigint unsigned DEFAULT NULL,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `path` longtext COLLATE utf8mb4_unicode_ci,
  `coordinates` longtext COLLATE utf8mb4_unicode_ci,
  `aktif` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `provinsi_negara_id_foreign` (`negara_id`),
  CONSTRAINT `provinsi_negara_id_foreign` FOREIGN KEY (`negara_id`) REFERENCES `country` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `role_user` */

DROP TABLE IF EXISTS `role_user`;

CREATE TABLE `role_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  KEY `role_user_user_id_foreign` (`user_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `guard_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT 'web',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_nama_guard_name_unique` (`nama`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatan` */

DROP TABLE IF EXISTS `trkegiatan`;

CREATE TABLE `trkegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `programoutcomeoutputactivity_id` bigint unsigned NOT NULL,
  `fasepelaporan` int DEFAULT '1',
  `jeniskegiatan_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `tanggalmulai` datetime NOT NULL,
  `tanggalselesai` datetime NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsilatarbelakang` text COLLATE utf8mb4_unicode_ci,
  `deskripsitujuan` text COLLATE utf8mb4_unicode_ci,
  `deskripsikeluaran` text COLLATE utf8mb4_unicode_ci,
  `deskripsiyangdikaji` text COLLATE utf8mb4_unicode_ci,
  `penerimamanfaatdewasaperempuan` int DEFAULT NULL,
  `penerimamanfaatdewasalakilaki` int DEFAULT NULL,
  `penerimamanfaatdewasatotal` int DEFAULT NULL,
  `penerimamanfaatlansiaperempuan` int DEFAULT NULL,
  `penerimamanfaatlansialakilaki` int DEFAULT NULL,
  `penerimamanfaatlansiatotal` int DEFAULT NULL,
  `penerimamanfaatremajaperempuan` int DEFAULT NULL,
  `penerimamanfaatremajalakilaki` int DEFAULT NULL,
  `penerimamanfaatremajatotal` int DEFAULT NULL,
  `penerimamanfaatanakperempuan` int DEFAULT NULL,
  `penerimamanfaatanaklakilaki` int DEFAULT NULL,
  `penerimamanfaatanaktotal` int DEFAULT NULL,
  `penerimamanfaatdisabilitasperempuan` int DEFAULT NULL,
  `penerimamanfaatdisabilitaslakilaki` int DEFAULT NULL,
  `penerimamanfaatdisabilitastotal` int DEFAULT NULL,
  `penerimamanfaatnondisabilitasperempuan` int DEFAULT NULL,
  `penerimamanfaatnondisabilitaslakilaki` int DEFAULT NULL,
  `penerimamanfaatnondisabilitastotal` int DEFAULT NULL,
  `penerimamanfaatmarjinalperempuan` int DEFAULT NULL,
  `penerimamanfaatmarjinallakilaki` int DEFAULT NULL,
  `penerimamanfaatmarjinaltotal` int DEFAULT NULL,
  `penerimamanfaatperempuantotal` int DEFAULT NULL,
  `penerimamanfaatlakilakitotal` int DEFAULT NULL,
  `penerimamanfaattotal` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatan_programoutcomeoutputactivity_id_foreign` (`programoutcomeoutputactivity_id`),
  KEY `trkegiatan_jeniskegiatan_id_foreign` (`jeniskegiatan_id`),
  KEY `trkegiatan_user_id_foreign` (`user_id`),
  CONSTRAINT `trkegiatan_jeniskegiatan_id_foreign` FOREIGN KEY (`jeniskegiatan_id`) REFERENCES `mjeniskegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatan_programoutcomeoutputactivity_id_foreign` FOREIGN KEY (`programoutcomeoutputactivity_id`) REFERENCES `trprogramoutcomeoutputactivity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatan_lokasi` */

DROP TABLE IF EXISTS `trkegiatan_lokasi`;

CREATE TABLE `trkegiatan_lokasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `desa_id` bigint unsigned DEFAULT NULL,
  `lokasi` text COLLATE utf8mb4_unicode_ci,
  `long` double DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatan_lokasi_kegiatan_id_foreign` (`kegiatan_id`),
  KEY `trkegiatan_lokasi_desa_id_foreign` (`desa_id`),
  CONSTRAINT `trkegiatan_lokasi_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `kelurahan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatan_lokasi_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatan_mitra` */

DROP TABLE IF EXISTS `trkegiatan_mitra`;

CREATE TABLE `trkegiatan_mitra` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `mitra_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatan_mitra_kegiatan_id_foreign` (`kegiatan_id`),
  KEY `trkegiatan_mitra_mitra_id_foreign` (`mitra_id`),
  CONSTRAINT `trkegiatan_mitra_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatan_mitra_mitra_id_foreign` FOREIGN KEY (`mitra_id`) REFERENCES `mpartner` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatan_sektor` */

DROP TABLE IF EXISTS `trkegiatan_sektor`;

CREATE TABLE `trkegiatan_sektor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned DEFAULT NULL,
  `sektor_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatan_sektor_kegiatan_id_foreign` (`kegiatan_id`),
  KEY `trkegiatan_sektor_sektor_id_foreign` (`sektor_id`),
  CONSTRAINT `trkegiatan_sektor_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatan_sektor_sektor_id_foreign` FOREIGN KEY (`sektor_id`) REFERENCES `mtargetreinstra` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanassessment` */

DROP TABLE IF EXISTS `trkegiatanassessment`;

CREATE TABLE `trkegiatanassessment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `assessmentyangterlibat` longtext COLLATE utf8mb4_unicode_ci,
  `assessmenttemuan` longtext COLLATE utf8mb4_unicode_ci,
  `assessmenttambahan` tinyint(1) NOT NULL DEFAULT '0',
  `assessmenttambahan_ket` longtext COLLATE utf8mb4_unicode_ci,
  `assessmentkendala` longtext COLLATE utf8mb4_unicode_ci,
  `assessmentisu` longtext COLLATE utf8mb4_unicode_ci,
  `assessmentpembelajaran` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanassessment_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanassessment_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatankampanye` */

DROP TABLE IF EXISTS `trkegiatankampanye`;

CREATE TABLE `trkegiatankampanye` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `kampanyeyangdikampanyekan` longtext COLLATE utf8mb4_unicode_ci,
  `kampanyejenis` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kampanyebentukkegiatan` longtext COLLATE utf8mb4_unicode_ci,
  `kampanyeyangterlibat` longtext COLLATE utf8mb4_unicode_ci,
  `kampanyeyangdisasar` longtext COLLATE utf8mb4_unicode_ci,
  `kampanyejangkauan` longtext COLLATE utf8mb4_unicode_ci,
  `kampanyerencana` longtext COLLATE utf8mb4_unicode_ci,
  `kampanyekendala` longtext COLLATE utf8mb4_unicode_ci,
  `kampanyeisu` longtext COLLATE utf8mb4_unicode_ci,
  `kampanyepembelajaran` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatankampanye_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatankampanye_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatankonsultasi` */

DROP TABLE IF EXISTS `trkegiatankonsultasi`;

CREATE TABLE `trkegiatankonsultasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `konsultasilembaga` longtext COLLATE utf8mb4_unicode_ci,
  `konsultasikomponen` longtext COLLATE utf8mb4_unicode_ci,
  `konsultasiyangdilakukan` longtext COLLATE utf8mb4_unicode_ci,
  `konsultasihasil` longtext COLLATE utf8mb4_unicode_ci,
  `konsultasipotensipendapatan` longtext COLLATE utf8mb4_unicode_ci,
  `konsultasirencana` longtext COLLATE utf8mb4_unicode_ci,
  `konsultasikendala` longtext COLLATE utf8mb4_unicode_ci,
  `konsultasiisu` longtext COLLATE utf8mb4_unicode_ci,
  `konsultasipembelajaran` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatankonsultasi_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatankonsultasi_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatankunjungan` */

DROP TABLE IF EXISTS `trkegiatankunjungan`;

CREATE TABLE `trkegiatankunjungan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `kunjunganlembaga` longtext COLLATE utf8mb4_unicode_ci,
  `kunjunganpeserta` longtext COLLATE utf8mb4_unicode_ci,
  `kunjunganyangdilakukan` longtext COLLATE utf8mb4_unicode_ci,
  `kunjunganhasil` longtext COLLATE utf8mb4_unicode_ci,
  `kunjunganpotensipendapatan` longtext COLLATE utf8mb4_unicode_ci,
  `kunjunganrencana` longtext COLLATE utf8mb4_unicode_ci,
  `kunjungankendala` longtext COLLATE utf8mb4_unicode_ci,
  `kunjunganisu` longtext COLLATE utf8mb4_unicode_ci,
  `kunjunganpembelajaran` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatankunjungan_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatankunjungan_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanlainnya` */

DROP TABLE IF EXISTS `trkegiatanlainnya`;

CREATE TABLE `trkegiatanlainnya` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `lainnyamengapadilakukan` longtext COLLATE utf8mb4_unicode_ci,
  `lainnyadampak` longtext COLLATE utf8mb4_unicode_ci,
  `lainnyasumberpendanaan` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lainnyasumberpendanaan_ket` longtext COLLATE utf8mb4_unicode_ci,
  `lainnyayangterlibat` longtext COLLATE utf8mb4_unicode_ci,
  `lainnyarencana` longtext COLLATE utf8mb4_unicode_ci,
  `lainnyakendala` longtext COLLATE utf8mb4_unicode_ci,
  `lainnyaisu` longtext COLLATE utf8mb4_unicode_ci,
  `lainnyapembelajaran` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanlainnya_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanlainnya_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanmonitoring` */

DROP TABLE IF EXISTS `trkegiatanmonitoring`;

CREATE TABLE `trkegiatanmonitoring` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `monitoringyangdipantau` longtext COLLATE utf8mb4_unicode_ci,
  `monitoringdata` longtext COLLATE utf8mb4_unicode_ci,
  `monitoringyangterlibat` longtext COLLATE utf8mb4_unicode_ci,
  `monitoringmetode` longtext COLLATE utf8mb4_unicode_ci,
  `monitoringhasil` longtext COLLATE utf8mb4_unicode_ci,
  `monitoringkegiatanselanjutnya` tinyint(1) NOT NULL DEFAULT '0',
  `monitoringkegiatanselanjutnya_ket` longtext COLLATE utf8mb4_unicode_ci,
  `monitoringkendala` longtext COLLATE utf8mb4_unicode_ci,
  `monitoringisu` longtext COLLATE utf8mb4_unicode_ci,
  `monitoringpembelajaran` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanmonitoring_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanmonitoring_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanpelatihan` */

DROP TABLE IF EXISTS `trkegiatanpelatihan`;

CREATE TABLE `trkegiatanpelatihan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `pelatihanpelatih` longtext COLLATE utf8mb4_unicode_ci,
  `pelatihanhasil` longtext COLLATE utf8mb4_unicode_ci,
  `pelatihandistribusi` tinyint(1) NOT NULL DEFAULT '0',
  `pelatihandistribusi_ket` longtext COLLATE utf8mb4_unicode_ci,
  `pelatihanrencana` longtext COLLATE utf8mb4_unicode_ci,
  `pelatihanunggahan` tinyint(1) NOT NULL DEFAULT '0',
  `pelatihanisu` longtext COLLATE utf8mb4_unicode_ci,
  `pelatihanpembelajaran` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanpelatihan_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanpelatihan_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanpembelanjaan` */

DROP TABLE IF EXISTS `trkegiatanpembelanjaan`;

CREATE TABLE `trkegiatanpembelanjaan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `pembelanjaandetailbarang` longtext COLLATE utf8mb4_unicode_ci,
  `pembelanjaanmulai` datetime DEFAULT NULL,
  `pembelanjaanselesai` datetime DEFAULT NULL,
  `pembelanjaandistribusimulai` datetime DEFAULT NULL,
  `pembelanjaandistribusiselesai` datetime DEFAULT NULL,
  `pembelanjaanterdistribusi` tinyint(1) NOT NULL DEFAULT '0',
  `pembelanjaanakandistribusi` tinyint(1) NOT NULL DEFAULT '0',
  `pembelanjaanakandistribusi_ket` longtext COLLATE utf8mb4_unicode_ci,
  `pembelanjaankendala` longtext COLLATE utf8mb4_unicode_ci,
  `pembelanjaanisu` longtext COLLATE utf8mb4_unicode_ci,
  `pembelanjaanpembelajaran` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanpembelanjaan_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanpembelanjaan_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanpemetaan` */

DROP TABLE IF EXISTS `trkegiatanpemetaan`;

CREATE TABLE `trkegiatanpemetaan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `pemetaanyangdihasilkan` longtext COLLATE utf8mb4_unicode_ci,
  `pemetaanluasan` longtext COLLATE utf8mb4_unicode_ci,
  `pemetaanunit` longtext COLLATE utf8mb4_unicode_ci,
  `pemetaanyangterlibat` longtext COLLATE utf8mb4_unicode_ci,
  `pemetaanrencana` longtext COLLATE utf8mb4_unicode_ci,
  `pemetaanisu` longtext COLLATE utf8mb4_unicode_ci,
  `pemetaanpembelajaran` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanpemetaan_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanpemetaan_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanpengembangan` */

DROP TABLE IF EXISTS `trkegiatanpengembangan`;

CREATE TABLE `trkegiatanpengembangan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `pengembanganjeniskomponen` longtext COLLATE utf8mb4_unicode_ci,
  `pengembanganberapakomponen` longtext COLLATE utf8mb4_unicode_ci,
  `pengembanganlokasikomponen` longtext COLLATE utf8mb4_unicode_ci,
  `pengembanganyangterlibat` longtext COLLATE utf8mb4_unicode_ci,
  `pengembanganrencana` longtext COLLATE utf8mb4_unicode_ci,
  `pengembangankendala` longtext COLLATE utf8mb4_unicode_ci,
  `pengembanganisu` longtext COLLATE utf8mb4_unicode_ci,
  `pengembanganpembelajaran` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanpengembangan_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanpengembangan_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanpenulis` */

DROP TABLE IF EXISTS `trkegiatanpenulis`;

CREATE TABLE `trkegiatanpenulis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `penulis_id` bigint unsigned NOT NULL,
  `peran_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanpenulis_kegiatan_id_foreign` (`kegiatan_id`),
  KEY `trkegiatanpenulis_peran_id_foreign` (`peran_id`),
  KEY `trkegiatanpenulis_user_id_foreign` (`penulis_id`),
  CONSTRAINT `trkegiatanpenulis_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatanpenulis_peran_id_foreign` FOREIGN KEY (`peran_id`) REFERENCES `mperan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatanpenulis_user_id_foreign` FOREIGN KEY (`penulis_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatansosialisasi` */

DROP TABLE IF EXISTS `trkegiatansosialisasi`;

CREATE TABLE `trkegiatansosialisasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `sosialisasiyangterlibat` longtext COLLATE utf8mb4_unicode_ci,
  `sosialisasitemuan` longtext COLLATE utf8mb4_unicode_ci,
  `sosialisasitambahan` tinyint(1) NOT NULL DEFAULT '0',
  `sosialisasitambahan_ket` longtext COLLATE utf8mb4_unicode_ci,
  `sosialisasikendala` longtext COLLATE utf8mb4_unicode_ci,
  `sosialisasiisu` longtext COLLATE utf8mb4_unicode_ci,
  `sosialisasipembelajaran` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatansosialisasi_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatansosialisasi_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_komponen_model` */

DROP TABLE IF EXISTS `trmeals_komponen_model`;

CREATE TABLE `trmeals_komponen_model` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `komponenmodel_id` bigint unsigned DEFAULT NULL,
  `totaljumlah` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_komponen_model_program_id_foreign` (`program_id`),
  KEY `trmeals_komponen_model_user_id_foreign` (`user_id`),
  KEY `trmeals_komponen_model_komponenmodel_id_foreign` (`komponenmodel_id`),
  CONSTRAINT `trmeals_komponen_model_komponenmodel_id_foreign` FOREIGN KEY (`komponenmodel_id`) REFERENCES `mkomponenmodel` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_komponen_model_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_komponen_model_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_komponen_model_lokasi` */

DROP TABLE IF EXISTS `trmeals_komponen_model_lokasi`;

CREATE TABLE `trmeals_komponen_model_lokasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mealskomponenmodel_id` bigint unsigned NOT NULL,
  `dusun_id` bigint unsigned DEFAULT NULL,
  `desa_id` bigint unsigned DEFAULT NULL,
  `kecamatan_id` bigint unsigned DEFAULT NULL,
  `kabupaten_id` bigint unsigned DEFAULT NULL,
  `provinsi_id` bigint unsigned DEFAULT NULL,
  `long` decimal(9,6) DEFAULT NULL,
  `lat` decimal(9,6) DEFAULT NULL,
  `satuan_id` bigint unsigned DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_komponen_model_lokasi_mealskomponenmodel_id_foreign` (`mealskomponenmodel_id`),
  KEY `trmeals_komponen_model_lokasi_dusun_id_foreign` (`dusun_id`),
  KEY `trmeals_komponen_model_lokasi_desa_id_foreign` (`desa_id`),
  KEY `trmeals_komponen_model_lokasi_kecamatan_id_foreign` (`kecamatan_id`),
  KEY `trmeals_komponen_model_lokasi_kabupaten_id_foreign` (`kabupaten_id`),
  KEY `trmeals_komponen_model_lokasi_provinsi_id_foreign` (`provinsi_id`),
  KEY `trmeals_komponen_model_lokasi_satuan_id_foreign` (`satuan_id`),
  CONSTRAINT `trmeals_komponen_model_lokasi_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `kelurahan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_komponen_model_lokasi_dusun_id_foreign` FOREIGN KEY (`dusun_id`) REFERENCES `dusun` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_komponen_model_lokasi_kabupaten_id_foreign` FOREIGN KEY (`kabupaten_id`) REFERENCES `kabupaten` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_komponen_model_lokasi_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_komponen_model_lokasi_mealskomponenmodel_id_foreign` FOREIGN KEY (`mealskomponenmodel_id`) REFERENCES `trmeals_komponen_model` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_komponen_model_lokasi_provinsi_id_foreign` FOREIGN KEY (`provinsi_id`) REFERENCES `provinsi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_komponen_model_lokasi_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `msatuan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_komponen_model_targetreinstra` */

DROP TABLE IF EXISTS `trmeals_komponen_model_targetreinstra`;

CREATE TABLE `trmeals_komponen_model_targetreinstra` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mealskomponenmodel_id` bigint unsigned NOT NULL,
  `targetreinstra_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mealskomodel_fk` (`mealskomponenmodel_id`),
  KEY `trmeals_komponen_model_targetreinstra_targetreinstra_id_foreign` (`targetreinstra_id`),
  CONSTRAINT `mealskomodel_fk` FOREIGN KEY (`mealskomponenmodel_id`) REFERENCES `trmeals_komponen_model` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_komponen_model_targetreinstra_targetreinstra_id_foreign` FOREIGN KEY (`targetreinstra_id`) REFERENCES `mtargetreinstra` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_penerima_manfaat` */

DROP TABLE IF EXISTS `trmeals_penerima_manfaat`;

CREATE TABLE `trmeals_penerima_manfaat` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `dusun_id` bigint unsigned NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rt` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rw` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `umur` int NOT NULL,
  `is_head_family` tinyint(1) NOT NULL DEFAULT '0',
  `head_family_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` longtext COLLATE utf8mb4_unicode_ci,
  `is_non_activity` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_penerima_manfaat_program_id_foreign` (`program_id`),
  KEY `trmeals_penerima_manfaat_user_id_foreign` (`user_id`),
  KEY `trmeals_penerima_manfaat_dusun_id_foreign` (`dusun_id`),
  CONSTRAINT `trmeals_penerima_manfaat_dusun_id_foreign` FOREIGN KEY (`dusun_id`) REFERENCES `dusun` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_penerima_manfaat_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_penerima_manfaat_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_penerima_manfaat_activity` */

DROP TABLE IF EXISTS `trmeals_penerima_manfaat_activity`;

CREATE TABLE `trmeals_penerima_manfaat_activity` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trmeals_penerima_manfaat_id` bigint unsigned NOT NULL,
  `programoutcomeoutputactivity_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_pm_fk` (`trmeals_penerima_manfaat_id`),
  KEY `trmeals_pm_activity_id_fk` (`programoutcomeoutputactivity_id`),
  CONSTRAINT `trmeals_pm_activity_id_fk` FOREIGN KEY (`programoutcomeoutputactivity_id`) REFERENCES `trprogramoutcomeoutputactivity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_pm_fk` FOREIGN KEY (`trmeals_penerima_manfaat_id`) REFERENCES `trmeals_penerima_manfaat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_penerima_manfaat_jenis_kelompok` */

DROP TABLE IF EXISTS `trmeals_penerima_manfaat_jenis_kelompok`;

CREATE TABLE `trmeals_penerima_manfaat_jenis_kelompok` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trmeals_penerima_manfaat_id` bigint unsigned NOT NULL,
  `jenis_kelompok_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_pm_mjk_fk` (`trmeals_penerima_manfaat_id`),
  KEY `trmeasl_mjk` (`jenis_kelompok_id`),
  CONSTRAINT `trmeals_pm_mjk_fk` FOREIGN KEY (`trmeals_penerima_manfaat_id`) REFERENCES `trmeals_penerima_manfaat` (`id`),
  CONSTRAINT `trmeasl_mjk` FOREIGN KEY (`jenis_kelompok_id`) REFERENCES `master_jenis_kelompok` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_penerima_manfaat_kelompok_marjinal` */

DROP TABLE IF EXISTS `trmeals_penerima_manfaat_kelompok_marjinal`;

CREATE TABLE `trmeals_penerima_manfaat_kelompok_marjinal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trmeals_penerima_manfaat_id` bigint unsigned NOT NULL,
  `kelompok_marjinal_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_pm_km_fk` (`trmeals_penerima_manfaat_id`),
  KEY `trmeals_km_pm_fk` (`kelompok_marjinal_id`),
  CONSTRAINT `trmeals_km_pm_fk` FOREIGN KEY (`kelompok_marjinal_id`) REFERENCES `mkelompokmarjinal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_pm_km_fk` FOREIGN KEY (`trmeals_penerima_manfaat_id`) REFERENCES `trmeals_penerima_manfaat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_target_progress` */

DROP TABLE IF EXISTS `trmeals_target_progress`;

CREATE TABLE `trmeals_target_progress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `tanggal` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_target_progress_program_id_foreign` (`program_id`),
  CONSTRAINT `trmeals_target_progress_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_target_progress_detail` */

DROP TABLE IF EXISTS `trmeals_target_progress_detail`;

CREATE TABLE `trmeals_target_progress_detail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_meals_target_progress` bigint unsigned NOT NULL,
  `targetable_id` bigint unsigned DEFAULT NULL,
  `targetable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `achievements` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `progress` int DEFAULT NULL,
  `persentase_complete` decimal(5,2) DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `challenges` text COLLATE utf8mb4_unicode_ci,
  `mitigation` text COLLATE utf8mb4_unicode_ci,
  `risk` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_target_progress_detail_id_meals_target_progress_foreign` (`id_meals_target_progress`),
  KEY `targetable_index` (`targetable_id`,`targetable_type`),
  CONSTRAINT `trmeals_target_progress_detail_id_meals_target_progress_foreign` FOREIGN KEY (`id_meals_target_progress`) REFERENCES `trmeals_target_progress` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmealsfrm` */

DROP TABLE IF EXISTS `trmealsfrm`;

CREATE TABLE `trmealsfrm` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `tanggalregistrasi` datetime NOT NULL,
  `umur` int NOT NULL,
  `jeniskelamin` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statuskomplain` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notelp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT '0',
  `userpenerima_id` bigint unsigned NOT NULL,
  `jabatanpenerima_id` bigint unsigned NOT NULL,
  `notelppenerima` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channels` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channelslainnya` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategorikomplain` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsikomplain` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggalselesai` datetime DEFAULT NULL,
  `userhandler_id` bigint unsigned NOT NULL,
  `jabatanhandler_id` bigint unsigned NOT NULL,
  `notelphandler` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmealsfrm_program_id_foreign` (`program_id`),
  KEY `trmealsfrm_userpenerima_id_foreign` (`userpenerima_id`),
  KEY `trmealsfrm_jabatanpenerima_id_foreign` (`jabatanpenerima_id`),
  KEY `trmealsfrm_userhandler_id_foreign` (`userhandler_id`),
  KEY `trmealsfrm_jabatanhandler_id_foreign` (`jabatanhandler_id`),
  CONSTRAINT `trmealsfrm_jabatanhandler_id_foreign` FOREIGN KEY (`jabatanhandler_id`) REFERENCES `mjabatan` (`id`),
  CONSTRAINT `trmealsfrm_jabatanpenerima_id_foreign` FOREIGN KEY (`jabatanpenerima_id`) REFERENCES `mjabatan` (`id`),
  CONSTRAINT `trmealsfrm_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`),
  CONSTRAINT `trmealsfrm_userhandler_id_foreign` FOREIGN KEY (`userhandler_id`) REFERENCES `users` (`id`),
  CONSTRAINT `trmealsfrm_userpenerima_id_foreign` FOREIGN KEY (`userpenerima_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmealspreposttest` */

DROP TABLE IF EXISTS `trmealspreposttest`;

CREATE TABLE `trmealspreposttest` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `programoutcomeoutputactivity_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `trainingname` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggalmulai` datetime NOT NULL,
  `tanggalselesai` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmealspreposttest_programoutcomeoutputactivity_id_foreign` (`programoutcomeoutputactivity_id`),
  KEY `trmealspreposttest_user_id_foreign` (`user_id`),
  CONSTRAINT `trmealspreposttest_programoutcomeoutputactivity_id_foreign` FOREIGN KEY (`programoutcomeoutputactivity_id`) REFERENCES `trprogramoutcomeoutputactivity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmealspreposttest_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmealspreposttestpeserta` */

DROP TABLE IF EXISTS `trmealspreposttestpeserta`;

CREATE TABLE `trmealspreposttestpeserta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `preposttest_id` bigint unsigned NOT NULL,
  `dusun_id` bigint unsigned NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jeniskelamin` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notelp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prescore` int DEFAULT NULL,
  `filedbytraineepre` tinyint(1) NOT NULL DEFAULT '0',
  `postscore` int DEFAULT NULL,
  `filedbytraineepost` tinyint(1) NOT NULL DEFAULT '0',
  `valuechange` int DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmealspreposttestpeserta_preposttest_id_foreign` (`preposttest_id`),
  KEY `trmealspreposttestpeserta_dusun_id_foreign` (`dusun_id`),
  CONSTRAINT `trmealspreposttestpeserta_dusun_id_foreign` FOREIGN KEY (`dusun_id`) REFERENCES `dusun` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmealspreposttestpeserta_preposttest_id_foreign` FOREIGN KEY (`preposttest_id`) REFERENCES `trmealspreposttest` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogram` */

DROP TABLE IF EXISTS `trprogram`;

CREATE TABLE `trprogram` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggalmulai` datetime NOT NULL,
  `tanggalselesai` datetime NOT NULL,
  `totalnilai` decimal(20,2) DEFAULT NULL,
  `ekspektasipenerimamanfaat` int DEFAULT NULL,
  `ekspektasipenerimamanfaatwoman` int DEFAULT NULL,
  `ekspektasipenerimamanfaatman` int DEFAULT NULL,
  `ekspektasipenerimamanfaatgirl` int DEFAULT NULL,
  `ekspektasipenerimamanfaatboy` int DEFAULT NULL,
  `ekspektasipenerimamanfaattidaklangsung` int DEFAULT NULL,
  `deskripsiprojek` longtext COLLATE utf8mb4_unicode_ci,
  `analisamasalah` longtext COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogram_user_id_foreign` (`user_id`),
  CONSTRAINT `trprogram_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramgoal` */

DROP TABLE IF EXISTS `trprogramgoal`;

CREATE TABLE `trprogramgoal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `deskripsi` longtext COLLATE utf8mb4_unicode_ci,
  `indikator` longtext COLLATE utf8mb4_unicode_ci,
  `target` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramgoal_program_id_foreign` (`program_id`),
  CONSTRAINT `trprogramgoal_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramkaitansdg` */

DROP TABLE IF EXISTS `trprogramkaitansdg`;

CREATE TABLE `trprogramkaitansdg` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `kaitansdg_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramkaitansdg_program_id_foreign` (`program_id`),
  KEY `trprogramkaitansdg_kaitansdg_id_foreign` (`kaitansdg_id`),
  CONSTRAINT `trprogramkaitansdg_kaitansdg_id_foreign` FOREIGN KEY (`kaitansdg_id`) REFERENCES `mkaitansdg` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramkaitansdg_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramkelompokmarjinal` */

DROP TABLE IF EXISTS `trprogramkelompokmarjinal`;

CREATE TABLE `trprogramkelompokmarjinal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `kelompokmarjinal_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramkelompokmarjinals_program_id_foreign` (`program_id`),
  KEY `trprogramkelompokmarjinals_kelompokmarjinal_id_foreign` (`kelompokmarjinal_id`),
  CONSTRAINT `trprogramkelompokmarjinals_kelompokmarjinal_id_foreign` FOREIGN KEY (`kelompokmarjinal_id`) REFERENCES `mkelompokmarjinal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramkelompokmarjinals_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramlokasi` */

DROP TABLE IF EXISTS `trprogramlokasi`;

CREATE TABLE `trprogramlokasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `provinsi_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramlokasis_program_id_foreign` (`program_id`),
  KEY `trprogramlokasis_provinsi_id_foreign` (`provinsi_id`),
  CONSTRAINT `trprogramlokasis_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramlokasis_provinsi_id_foreign` FOREIGN KEY (`provinsi_id`) REFERENCES `provinsi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramobjektif` */

DROP TABLE IF EXISTS `trprogramobjektif`;

CREATE TABLE `trprogramobjektif` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `deskripsi` longtext COLLATE utf8mb4_unicode_ci,
  `indikator` longtext COLLATE utf8mb4_unicode_ci,
  `target` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramobjektif_program_id_foreign` (`program_id`),
  CONSTRAINT `trprogramobjektif_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramoutcome` */

DROP TABLE IF EXISTS `trprogramoutcome`;

CREATE TABLE `trprogramoutcome` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `deskripsi` longtext COLLATE utf8mb4_unicode_ci,
  `indikator` longtext COLLATE utf8mb4_unicode_ci,
  `target` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramoutcome_program_id_foreign` (`program_id`),
  CONSTRAINT `trprogramoutcome_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramoutcomeoutput` */

DROP TABLE IF EXISTS `trprogramoutcomeoutput`;

CREATE TABLE `trprogramoutcomeoutput` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `programoutcome_id` bigint unsigned NOT NULL,
  `deskripsi` longtext COLLATE utf8mb4_unicode_ci,
  `indikator` longtext COLLATE utf8mb4_unicode_ci,
  `target` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramoutcomeoutput_programoutcome_id_foreign` (`programoutcome_id`),
  CONSTRAINT `trprogramoutcomeoutput_programoutcome_id_foreign` FOREIGN KEY (`programoutcome_id`) REFERENCES `trprogramoutcome` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramoutcomeoutputactivity` */

DROP TABLE IF EXISTS `trprogramoutcomeoutputactivity`;

CREATE TABLE `trprogramoutcomeoutputactivity` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `programoutcomeoutput_id` bigint unsigned NOT NULL,
  `deskripsi` longtext COLLATE utf8mb4_unicode_ci,
  `indikator` longtext COLLATE utf8mb4_unicode_ci,
  `target` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramoutcomeoutputactivity_programoutcomeoutput_id_foreign` (`programoutcomeoutput_id`),
  CONSTRAINT `trprogramoutcomeoutputactivity_programoutcomeoutput_id_foreign` FOREIGN KEY (`programoutcomeoutput_id`) REFERENCES `trprogramoutcomeoutput` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogrampartner` */

DROP TABLE IF EXISTS `trprogrampartner`;

CREATE TABLE `trprogrampartner` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `partner_id` bigint unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogrampartner_program_id_foreign` (`program_id`),
  KEY `trprogrampartner_partner_id_foreign` (`partner_id`),
  CONSTRAINT `trprogrampartner_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `mpartner` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogrampartner_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogrampendonor` */

DROP TABLE IF EXISTS `trprogrampendonor`;

CREATE TABLE `trprogrampendonor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `pendonor_id` bigint unsigned NOT NULL,
  `nilaidonasi` decimal(20,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogrampendonor_program_id_foreign` (`program_id`),
  KEY `trprogrampendonor_pendonor_id_foreign` (`pendonor_id`),
  CONSTRAINT `trprogrampendonor_pendonor_id_foreign` FOREIGN KEY (`pendonor_id`) REFERENCES `mpendonor` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogrampendonor_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramreportschedule` */

DROP TABLE IF EXISTS `trprogramreportschedule`;

CREATE TABLE `trprogramreportschedule` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramreportschedule_program_id_foreign` (`program_id`),
  CONSTRAINT `trprogramreportschedule_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramtargetreinstra` */

DROP TABLE IF EXISTS `trprogramtargetreinstra`;

CREATE TABLE `trprogramtargetreinstra` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `targetreinstra_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramtargetreinstra_program_id_foreign` (`program_id`),
  KEY `trprogramtargetreinstra_targetreinstra_id_foreign` (`targetreinstra_id`),
  CONSTRAINT `trprogramtargetreinstra_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramtargetreinstra_targetreinstra_id_foreign` FOREIGN KEY (`targetreinstra_id`) REFERENCES `mtargetreinstra` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramuser` */

DROP TABLE IF EXISTS `trprogramuser`;

CREATE TABLE `trprogramuser` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `peran_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramuser_program_id_foreign` (`program_id`),
  KEY `trprogramuser_user_id_foreign` (`user_id`),
  KEY `trprogramuser_peran_id_foreign` (`peran_id`),
  CONSTRAINT `trprogramuser_peran_id_foreign` FOREIGN KEY (`peran_id`) REFERENCES `mperan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramuser_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramuser_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan_id` bigint unsigned DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_jabatan_id_foreign` (`jabatan_id`),
  CONSTRAINT `users_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `mjabatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `wilayah` */

DROP TABLE IF EXISTS `wilayah`;

CREATE TABLE `wilayah` (
  `kode` varchar(10) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `provinsi` varchar(255) DEFAULT NULL,
  `geom` multipolygon /*!80003 SRID 4326 */ DEFAULT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
