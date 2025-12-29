/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.6.22-MariaDB-0ubuntu0.22.04.1 : Database - idepdev
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`idepdev` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `idepdev`;

/*Table structure for table `activity_log` */

DROP TABLE IF EXISTS `activity_log`;

CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` longtext NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=427 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `audit_logs` */

DROP TABLE IF EXISTS `audit_logs`;

CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` longtext NOT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext NOT NULL,
  `host` varchar(46) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1061 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `benchmark` */

DROP TABLE IF EXISTS `benchmark`;

CREATE TABLE `benchmark` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `jeniskegiatan_id` bigint(20) unsigned NOT NULL,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `desa_id` bigint(20) unsigned NOT NULL,
  `kecamatan_id` bigint(20) unsigned NOT NULL,
  `kabupaten_id` bigint(20) unsigned NOT NULL,
  `provinsi_id` bigint(20) unsigned NOT NULL,
  `tanggalimplementasi` date NOT NULL,
  `handler` varchar(500) NOT NULL,
  `usercompiler_id` bigint(20) unsigned NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `catatanevaluasi` varchar(500) DEFAULT NULL,
  `area` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `benchmark_program_id_foreign` (`program_id`),
  KEY `benchmark_jeniskegiatan_id_foreign` (`jeniskegiatan_id`),
  KEY `benchmark_kegiatan_id_foreign` (`kegiatan_id`),
  KEY `benchmark_desa_id_foreign` (`desa_id`),
  KEY `benchmark_kecamatan_id_foreign` (`kecamatan_id`),
  KEY `benchmark_kabupaten_id_foreign` (`kabupaten_id`),
  KEY `benchmark_provinsi_id_foreign` (`provinsi_id`),
  KEY `benchmark_usercompiler_id_foreign` (`usercompiler_id`),
  CONSTRAINT `benchmark_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `kelurahan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `benchmark_jeniskegiatan_id_foreign` FOREIGN KEY (`jeniskegiatan_id`) REFERENCES `mjeniskegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `benchmark_kabupaten_id_foreign` FOREIGN KEY (`kabupaten_id`) REFERENCES `kabupaten` (`id`) ON DELETE CASCADE,
  CONSTRAINT `benchmark_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `benchmark_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `benchmark_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE,
  CONSTRAINT `benchmark_provinsi_id_foreign` FOREIGN KEY (`provinsi_id`) REFERENCES `provinsi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `benchmark_usercompiler_id_foreign` FOREIGN KEY (`usercompiler_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `country` */

DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `iso1` varchar(10) NOT NULL,
  `iso2` varchar(10) NOT NULL,
  `flag` varchar(20) NOT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `dusun` */

DROP TABLE IF EXISTS `dusun`;

CREATE TABLE `dusun` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(16) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `aktif` int(11) DEFAULT 0,
  `desa_id` bigint(20) unsigned NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `coordinates` longtext DEFAULT NULL,
  `kode_pos` varchar(5) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dusun_desa_id_foreign` (`desa_id`),
  CONSTRAINT `dusun_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `kelurahan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

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

/*Table structure for table `feedback` */

DROP TABLE IF EXISTS `feedback`;

CREATE TABLE `feedback` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program` varchar(255) DEFAULT NULL,
  `tanggal_registrasi` date NOT NULL,
  `field_office` varchar(255) DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `penerima` varchar(255) DEFAULT NULL,
  `sort_of_complaint` varchar(255) NOT NULL,
  `age_group` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `sex` varchar(50) DEFAULT NULL,
  `kontak_penerima` varchar(255) DEFAULT NULL,
  `handler_id` bigint(20) unsigned DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `channels` varchar(255) DEFAULT NULL,
  `position_handler` varchar(255) DEFAULT NULL,
  `handler_description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `other_channel` varchar(255) DEFAULT NULL,
  `kontak_handler` varchar(255) DEFAULT NULL,
  `kategori_komplain` varchar(255) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `status_complaint` varchar(50) DEFAULT 'Baru',
  `is_hidden` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `program_id` bigint(20) unsigned DEFAULT NULL,
  `nama_pelapor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feedback_program_id_foreign` (`program_id`),
  KEY `feedback_handler_id_foreign` (`handler_id`),
  CONSTRAINT `feedback_handler_id_foreign` FOREIGN KEY (`handler_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `feedback_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `kabupaten` */

DROP TABLE IF EXISTS `kabupaten`;

CREATE TABLE `kabupaten` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `type` varchar(15) NOT NULL DEFAULT 'kabupaten',
  `nama` varchar(200) NOT NULL,
  `kota` varchar(255) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `path` longtext DEFAULT NULL,
  `coordinates` longtext DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 0,
  `provinsi_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kabupaten_provinsi_id_foreign` (`provinsi_id`),
  CONSTRAINT `kabupaten_provinsi_id_foreign` FOREIGN KEY (`provinsi_id`) REFERENCES `provinsi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9509 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `kecamatan` */

DROP TABLE IF EXISTS `kecamatan`;

CREATE TABLE `kecamatan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `aktif` tinyint(1) DEFAULT 0,
  `kabupaten_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kecamatan_kabupaten_id_foreign` (`kabupaten_id`),
  CONSTRAINT `kecamatan_kabupaten_id_foreign` FOREIGN KEY (`kabupaten_id`) REFERENCES `kabupaten` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=950833 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `kelurahan` */

DROP TABLE IF EXISTS `kelurahan`;

CREATE TABLE `kelurahan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `aktif` int(11) DEFAULT 0,
  `kecamatan_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelurahan_kecamatan_id_foreign` (`kecamatan_id`),
  CONSTRAINT `kelurahan_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9508322005 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `master_jenis_kelompok` */

DROP TABLE IF EXISTS `master_jenis_kelompok`;

CREATE TABLE `master_jenis_kelompok` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `master_jenis_kelompok_nama_unique` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `media` */

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mjabatan` */

DROP TABLE IF EXISTS `mjabatan`;

CREATE TABLE `mjabatan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mjenisbantuan` */

DROP TABLE IF EXISTS `mjenisbantuan`;

CREATE TABLE `mjenisbantuan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mjeniskegiatan` */

DROP TABLE IF EXISTS `mjeniskegiatan`;

CREATE TABLE `mjeniskegiatan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mkaitansdg` */

DROP TABLE IF EXISTS `mkaitansdg`;

CREATE TABLE `mkaitansdg` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mkategorilokasikegiatan` */

DROP TABLE IF EXISTS `mkategorilokasikegiatan`;

CREATE TABLE `mkategorilokasikegiatan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mkelompokmarjinal` */

DROP TABLE IF EXISTS `mkelompokmarjinal`;

CREATE TABLE `mkelompokmarjinal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `aktif` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mkomponenmodel` */

DROP TABLE IF EXISTS `mkomponenmodel`;

CREATE TABLE `mkomponenmodel` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mpartner` */

DROP TABLE IF EXISTS `mpartner`;

CREATE TABLE `mpartner` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `keterangan` longtext DEFAULT NULL,
  `aktif` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mpendonor` */

DROP TABLE IF EXISTS `mpendonor`;

CREATE TABLE `mpendonor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mpendonorkategori_id` bigint(20) unsigned NOT NULL,
  `nama` varchar(200) NOT NULL,
  `pic` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `aktif` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mpendonor_mpendonorkategori_id_foreign` (`mpendonorkategori_id`),
  CONSTRAINT `mpendonor_mpendonorkategori_id_foreign` FOREIGN KEY (`mpendonorkategori_id`) REFERENCES `mpendonorkategori` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mpendonorkategori` */

DROP TABLE IF EXISTS `mpendonorkategori`;

CREATE TABLE `mpendonorkategori` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mperan` */

DROP TABLE IF EXISTS `mperan`;

CREATE TABLE `mperan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `msatuan` */

DROP TABLE IF EXISTS `msatuan`;

CREATE TABLE `msatuan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `aktif` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `msektor` */

DROP TABLE IF EXISTS `msektor`;

CREATE TABLE `msektor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mtargetreinstra` */

DROP TABLE IF EXISTS `mtargetreinstra`;

CREATE TABLE `mtargetreinstra` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `aktif` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `permission_role` */

DROP TABLE IF EXISTS `permission_role`;

CREATE TABLE `permission_role` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_role_permission_id_foreign` (`permission_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=407 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `guard_name` varchar(200) DEFAULT 'web',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_nama_guard_name_unique` (`nama`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
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
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `negara_id` bigint(20) unsigned DEFAULT NULL,
  `nama` varchar(200) NOT NULL,
  `kota` varchar(255) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `path` longtext DEFAULT NULL,
  `coordinates` longtext DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `provinsi_negara_id_foreign` (`negara_id`),
  CONSTRAINT `provinsi_negara_id_foreign` FOREIGN KEY (`negara_id`) REFERENCES `country` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `role_user` */

DROP TABLE IF EXISTS `role_user`;

CREATE TABLE `role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  KEY `role_user_user_id_foreign` (`user_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `guard_name` varchar(200) DEFAULT 'web',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_nama_guard_name_unique` (`nama`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

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

/*Table structure for table `trkegiatan` */

DROP TABLE IF EXISTS `trkegiatan`;

CREATE TABLE `trkegiatan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `programoutcomeoutputactivity_id` bigint(20) unsigned NOT NULL,
  `fasepelaporan` int(11) DEFAULT 1,
  `jeniskegiatan_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `tanggalmulai` datetime NOT NULL,
  `tanggalselesai` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `deskripsilatarbelakang` text DEFAULT NULL,
  `deskripsitujuan` text DEFAULT NULL,
  `deskripsikeluaran` text DEFAULT NULL,
  `deskripsiyangdikaji` text DEFAULT NULL,
  `penerimamanfaatdewasaperempuan` int(11) DEFAULT NULL,
  `penerimamanfaatdewasalakilaki` int(11) DEFAULT NULL,
  `penerimamanfaatdewasatotal` int(11) DEFAULT NULL,
  `penerimamanfaatlansiaperempuan` int(11) DEFAULT NULL,
  `penerimamanfaatlansialakilaki` int(11) DEFAULT NULL,
  `penerimamanfaatlansiatotal` int(11) DEFAULT NULL,
  `penerimamanfaatremajaperempuan` int(11) DEFAULT NULL,
  `penerimamanfaatremajalakilaki` int(11) DEFAULT NULL,
  `penerimamanfaatremajatotal` int(11) DEFAULT NULL,
  `penerimamanfaatanakperempuan` int(11) DEFAULT NULL,
  `penerimamanfaatanaklakilaki` int(11) DEFAULT NULL,
  `penerimamanfaatanaktotal` int(11) DEFAULT NULL,
  `penerimamanfaatdisabilitasperempuan` int(11) DEFAULT NULL,
  `penerimamanfaatdisabilitaslakilaki` int(11) DEFAULT NULL,
  `penerimamanfaatdisabilitastotal` int(11) DEFAULT NULL,
  `penerimamanfaatnondisabilitasperempuan` int(11) DEFAULT NULL,
  `penerimamanfaatnondisabilitaslakilaki` int(11) DEFAULT NULL,
  `penerimamanfaatnondisabilitastotal` int(11) DEFAULT NULL,
  `penerimamanfaatmarjinalperempuan` int(11) DEFAULT NULL,
  `penerimamanfaatmarjinallakilaki` int(11) DEFAULT NULL,
  `penerimamanfaatmarjinaltotal` int(11) DEFAULT NULL,
  `penerimamanfaatperempuantotal` int(11) DEFAULT NULL,
  `penerimamanfaatlakilakitotal` int(11) DEFAULT NULL,
  `penerimamanfaattotal` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatan_programoutcomeoutputactivity_id_foreign` (`programoutcomeoutputactivity_id`),
  KEY `trkegiatan_jeniskegiatan_id_foreign` (`jeniskegiatan_id`),
  KEY `trkegiatan_user_id_foreign` (`user_id`),
  CONSTRAINT `trkegiatan_jeniskegiatan_id_foreign` FOREIGN KEY (`jeniskegiatan_id`) REFERENCES `mjeniskegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatan_programoutcomeoutputactivity_id_foreign` FOREIGN KEY (`programoutcomeoutputactivity_id`) REFERENCES `trprogramoutcomeoutputactivity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatan_lokasi` */

DROP TABLE IF EXISTS `trkegiatan_lokasi`;

CREATE TABLE `trkegiatan_lokasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `desa_id` bigint(20) unsigned DEFAULT NULL,
  `lokasi` text DEFAULT NULL,
  `long` double DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatan_lokasi_kegiatan_id_foreign` (`kegiatan_id`),
  KEY `trkegiatan_lokasi_desa_id_foreign` (`desa_id`),
  CONSTRAINT `trkegiatan_lokasi_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `kelurahan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatan_lokasi_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatan_mitra` */

DROP TABLE IF EXISTS `trkegiatan_mitra`;

CREATE TABLE `trkegiatan_mitra` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `mitra_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatan_mitra_kegiatan_id_foreign` (`kegiatan_id`),
  KEY `trkegiatan_mitra_mitra_id_foreign` (`mitra_id`),
  CONSTRAINT `trkegiatan_mitra_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatan_mitra_mitra_id_foreign` FOREIGN KEY (`mitra_id`) REFERENCES `mpartner` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatan_sektor` */

DROP TABLE IF EXISTS `trkegiatan_sektor`;

CREATE TABLE `trkegiatan_sektor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned DEFAULT NULL,
  `sektor_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatan_sektor_kegiatan_id_foreign` (`kegiatan_id`),
  KEY `trkegiatan_sektor_sektor_id_foreign` (`sektor_id`),
  CONSTRAINT `trkegiatan_sektor_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatan_sektor_sektor_id_foreign` FOREIGN KEY (`sektor_id`) REFERENCES `mtargetreinstra` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanassessment` */

DROP TABLE IF EXISTS `trkegiatanassessment`;

CREATE TABLE `trkegiatanassessment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `assessmentyangterlibat` longtext DEFAULT NULL,
  `assessmenttemuan` longtext DEFAULT NULL,
  `assessmenttambahan` tinyint(1) NOT NULL DEFAULT 0,
  `assessmenttambahan_ket` longtext DEFAULT NULL,
  `assessmentkendala` longtext DEFAULT NULL,
  `assessmentisu` longtext DEFAULT NULL,
  `assessmentpembelajaran` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanassessment_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanassessment_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatankampanye` */

DROP TABLE IF EXISTS `trkegiatankampanye`;

CREATE TABLE `trkegiatankampanye` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `kampanyeyangdikampanyekan` longtext DEFAULT NULL,
  `kampanyejenis` varchar(200) DEFAULT NULL,
  `kampanyebentukkegiatan` longtext DEFAULT NULL,
  `kampanyeyangterlibat` longtext DEFAULT NULL,
  `kampanyeyangdisasar` longtext DEFAULT NULL,
  `kampanyejangkauan` longtext DEFAULT NULL,
  `kampanyerencana` longtext DEFAULT NULL,
  `kampanyekendala` longtext DEFAULT NULL,
  `kampanyeisu` longtext DEFAULT NULL,
  `kampanyepembelajaran` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatankampanye_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatankampanye_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatankonsultasi` */

DROP TABLE IF EXISTS `trkegiatankonsultasi`;

CREATE TABLE `trkegiatankonsultasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `konsultasilembaga` longtext DEFAULT NULL,
  `konsultasikomponen` longtext DEFAULT NULL,
  `konsultasiyangdilakukan` longtext DEFAULT NULL,
  `konsultasihasil` longtext DEFAULT NULL,
  `konsultasipotensipendapatan` longtext DEFAULT NULL,
  `konsultasirencana` longtext DEFAULT NULL,
  `konsultasikendala` longtext DEFAULT NULL,
  `konsultasiisu` longtext DEFAULT NULL,
  `konsultasipembelajaran` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatankonsultasi_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatankonsultasi_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatankunjungan` */

DROP TABLE IF EXISTS `trkegiatankunjungan`;

CREATE TABLE `trkegiatankunjungan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `kunjunganlembaga` longtext DEFAULT NULL,
  `kunjunganpeserta` longtext DEFAULT NULL,
  `kunjunganyangdilakukan` longtext DEFAULT NULL,
  `kunjunganhasil` longtext DEFAULT NULL,
  `kunjunganpotensipendapatan` longtext DEFAULT NULL,
  `kunjunganrencana` longtext DEFAULT NULL,
  `kunjungankendala` longtext DEFAULT NULL,
  `kunjunganisu` longtext DEFAULT NULL,
  `kunjunganpembelajaran` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatankunjungan_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatankunjungan_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanlainnya` */

DROP TABLE IF EXISTS `trkegiatanlainnya`;

CREATE TABLE `trkegiatanlainnya` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `lainnyamengapadilakukan` longtext DEFAULT NULL,
  `lainnyadampak` longtext DEFAULT NULL,
  `lainnyasumberpendanaan` varchar(200) DEFAULT NULL,
  `lainnyasumberpendanaan_ket` longtext DEFAULT NULL,
  `lainnyayangterlibat` longtext DEFAULT NULL,
  `lainnyarencana` longtext DEFAULT NULL,
  `lainnyakendala` longtext DEFAULT NULL,
  `lainnyaisu` longtext DEFAULT NULL,
  `lainnyapembelajaran` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanlainnya_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanlainnya_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanmonitoring` */

DROP TABLE IF EXISTS `trkegiatanmonitoring`;

CREATE TABLE `trkegiatanmonitoring` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `monitoringyangdipantau` longtext DEFAULT NULL,
  `monitoringdata` longtext DEFAULT NULL,
  `monitoringyangterlibat` longtext DEFAULT NULL,
  `monitoringmetode` longtext DEFAULT NULL,
  `monitoringhasil` longtext DEFAULT NULL,
  `monitoringkegiatanselanjutnya` tinyint(1) NOT NULL DEFAULT 0,
  `monitoringkegiatanselanjutnya_ket` longtext DEFAULT NULL,
  `monitoringkendala` longtext DEFAULT NULL,
  `monitoringisu` longtext DEFAULT NULL,
  `monitoringpembelajaran` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanmonitoring_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanmonitoring_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanpelatihan` */

DROP TABLE IF EXISTS `trkegiatanpelatihan`;

CREATE TABLE `trkegiatanpelatihan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `pelatihanpelatih` longtext DEFAULT NULL,
  `pelatihanhasil` longtext DEFAULT NULL,
  `pelatihandistribusi` tinyint(1) NOT NULL DEFAULT 0,
  `pelatihandistribusi_ket` longtext DEFAULT NULL,
  `pelatihanrencana` longtext DEFAULT NULL,
  `pelatihanunggahan` tinyint(1) NOT NULL DEFAULT 0,
  `pelatihanisu` longtext DEFAULT NULL,
  `pelatihanpembelajaran` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanpelatihan_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanpelatihan_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanpembelanjaan` */

DROP TABLE IF EXISTS `trkegiatanpembelanjaan`;

CREATE TABLE `trkegiatanpembelanjaan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `pembelanjaandetailbarang` longtext DEFAULT NULL,
  `pembelanjaanmulai` datetime DEFAULT NULL,
  `pembelanjaanselesai` datetime DEFAULT NULL,
  `pembelanjaandistribusimulai` datetime DEFAULT NULL,
  `pembelanjaandistribusiselesai` datetime DEFAULT NULL,
  `pembelanjaanterdistribusi` tinyint(1) NOT NULL DEFAULT 0,
  `pembelanjaanakandistribusi` tinyint(1) NOT NULL DEFAULT 0,
  `pembelanjaanakandistribusi_ket` longtext DEFAULT NULL,
  `pembelanjaankendala` longtext DEFAULT NULL,
  `pembelanjaanisu` longtext DEFAULT NULL,
  `pembelanjaanpembelajaran` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanpembelanjaan_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanpembelanjaan_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanpemetaan` */

DROP TABLE IF EXISTS `trkegiatanpemetaan`;

CREATE TABLE `trkegiatanpemetaan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `pemetaanyangdihasilkan` longtext DEFAULT NULL,
  `pemetaanluasan` longtext DEFAULT NULL,
  `pemetaanunit` longtext DEFAULT NULL,
  `pemetaanyangterlibat` longtext DEFAULT NULL,
  `pemetaanrencana` longtext DEFAULT NULL,
  `pemetaanisu` longtext DEFAULT NULL,
  `pemetaanpembelajaran` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanpemetaan_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanpemetaan_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanpengembangan` */

DROP TABLE IF EXISTS `trkegiatanpengembangan`;

CREATE TABLE `trkegiatanpengembangan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `pengembanganjeniskomponen` longtext DEFAULT NULL,
  `pengembanganberapakomponen` longtext DEFAULT NULL,
  `pengembanganlokasikomponen` longtext DEFAULT NULL,
  `pengembanganyangterlibat` longtext DEFAULT NULL,
  `pengembanganrencana` longtext DEFAULT NULL,
  `pengembangankendala` longtext DEFAULT NULL,
  `pengembanganisu` longtext DEFAULT NULL,
  `pengembanganpembelajaran` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanpengembangan_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatanpengembangan_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatanpenulis` */

DROP TABLE IF EXISTS `trkegiatanpenulis`;

CREATE TABLE `trkegiatanpenulis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `penulis_id` bigint(20) unsigned NOT NULL,
  `peran_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatanpenulis_kegiatan_id_foreign` (`kegiatan_id`),
  KEY `trkegiatanpenulis_user_id_foreign` (`penulis_id`),
  KEY `trkegiatanpenulis_peran_id_foreign` (`peran_id`),
  CONSTRAINT `trkegiatanpenulis_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatanpenulis_peran_id_foreign` FOREIGN KEY (`peran_id`) REFERENCES `mperan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trkegiatanpenulis_user_id_foreign` FOREIGN KEY (`penulis_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trkegiatansosialisasi` */

DROP TABLE IF EXISTS `trkegiatansosialisasi`;

CREATE TABLE `trkegiatansosialisasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint(20) unsigned NOT NULL,
  `sosialisasiyangterlibat` longtext DEFAULT NULL,
  `sosialisasitemuan` longtext DEFAULT NULL,
  `sosialisasitambahan` tinyint(1) NOT NULL DEFAULT 0,
  `sosialisasitambahan_ket` longtext DEFAULT NULL,
  `sosialisasikendala` longtext DEFAULT NULL,
  `sosialisasiisu` longtext DEFAULT NULL,
  `sosialisasipembelajaran` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trkegiatansosialisasi_kegiatan_id_foreign` (`kegiatan_id`),
  CONSTRAINT `trkegiatansosialisasi_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `trkegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_komponen_model` */

DROP TABLE IF EXISTS `trmeals_komponen_model`;

CREATE TABLE `trmeals_komponen_model` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `komponenmodel_id` bigint(20) unsigned DEFAULT NULL,
  `totaljumlah` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_komponen_model_lokasi` */

DROP TABLE IF EXISTS `trmeals_komponen_model_lokasi`;

CREATE TABLE `trmeals_komponen_model_lokasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mealskomponenmodel_id` bigint(20) unsigned NOT NULL,
  `dusun_id` bigint(20) unsigned DEFAULT NULL,
  `desa_id` bigint(20) unsigned DEFAULT NULL,
  `kecamatan_id` bigint(20) unsigned DEFAULT NULL,
  `kabupaten_id` bigint(20) unsigned DEFAULT NULL,
  `provinsi_id` bigint(20) unsigned DEFAULT NULL,
  `long` decimal(9,6) DEFAULT NULL,
  `lat` decimal(9,6) DEFAULT NULL,
  `satuan_id` bigint(20) unsigned DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_komponen_model_targetreinstra` */

DROP TABLE IF EXISTS `trmeals_komponen_model_targetreinstra`;

CREATE TABLE `trmeals_komponen_model_targetreinstra` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mealskomponenmodel_id` bigint(20) unsigned NOT NULL,
  `targetreinstra_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mealskomodel_fk` (`mealskomponenmodel_id`),
  KEY `trmeals_komponen_model_targetreinstra_targetreinstra_id_foreign` (`targetreinstra_id`),
  CONSTRAINT `mealskomodel_fk` FOREIGN KEY (`mealskomponenmodel_id`) REFERENCES `trmeals_komponen_model` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_komponen_model_targetreinstra_targetreinstra_id_foreign` FOREIGN KEY (`targetreinstra_id`) REFERENCES `mtargetreinstra` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_penerima_manfaat` */

DROP TABLE IF EXISTS `trmeals_penerima_manfaat`;

CREATE TABLE `trmeals_penerima_manfaat` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `dusun_id` bigint(20) unsigned NOT NULL,
  `nama` varchar(255) NOT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `jenis_kelamin` varchar(20) NOT NULL,
  `rt` varchar(10) NOT NULL,
  `rw` varchar(10) NOT NULL,
  `umur` int(11) NOT NULL,
  `is_head_family` tinyint(1) NOT NULL DEFAULT 0,
  `head_family_name` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `is_non_activity` tinyint(1) NOT NULL DEFAULT 0,
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_penerima_manfaat_activity` */

DROP TABLE IF EXISTS `trmeals_penerima_manfaat_activity`;

CREATE TABLE `trmeals_penerima_manfaat_activity` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `trmeals_penerima_manfaat_id` bigint(20) unsigned NOT NULL,
  `programoutcomeoutputactivity_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_pm_fk` (`trmeals_penerima_manfaat_id`),
  KEY `trmeals_pm_activity_id_fk` (`programoutcomeoutputactivity_id`),
  CONSTRAINT `trmeals_pm_activity_id_fk` FOREIGN KEY (`programoutcomeoutputactivity_id`) REFERENCES `trprogramoutcomeoutputactivity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_pm_fk` FOREIGN KEY (`trmeals_penerima_manfaat_id`) REFERENCES `trmeals_penerima_manfaat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_penerima_manfaat_jenis_kelompok` */

DROP TABLE IF EXISTS `trmeals_penerima_manfaat_jenis_kelompok`;

CREATE TABLE `trmeals_penerima_manfaat_jenis_kelompok` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `trmeals_penerima_manfaat_id` bigint(20) unsigned NOT NULL,
  `jenis_kelompok_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_pm_mjk_fk` (`trmeals_penerima_manfaat_id`),
  KEY `trmeasl_mjk` (`jenis_kelompok_id`),
  CONSTRAINT `trmeals_pm_mjk_fk` FOREIGN KEY (`trmeals_penerima_manfaat_id`) REFERENCES `trmeals_penerima_manfaat` (`id`),
  CONSTRAINT `trmeasl_mjk` FOREIGN KEY (`jenis_kelompok_id`) REFERENCES `master_jenis_kelompok` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_penerima_manfaat_kelompok_marjinal` */

DROP TABLE IF EXISTS `trmeals_penerima_manfaat_kelompok_marjinal`;

CREATE TABLE `trmeals_penerima_manfaat_kelompok_marjinal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `trmeals_penerima_manfaat_id` bigint(20) unsigned NOT NULL,
  `kelompok_marjinal_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_pm_km_fk` (`trmeals_penerima_manfaat_id`),
  KEY `trmeals_km_pm_fk` (`kelompok_marjinal_id`),
  CONSTRAINT `trmeals_km_pm_fk` FOREIGN KEY (`kelompok_marjinal_id`) REFERENCES `mkelompokmarjinal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmeals_pm_km_fk` FOREIGN KEY (`trmeals_penerima_manfaat_id`) REFERENCES `trmeals_penerima_manfaat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_target_progress` */

DROP TABLE IF EXISTS `trmeals_target_progress`;

CREATE TABLE `trmeals_target_progress` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `tanggal` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_target_progress_program_id_foreign` (`program_id`),
  CONSTRAINT `trmeals_target_progress_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmeals_target_progress_detail` */

DROP TABLE IF EXISTS `trmeals_target_progress_detail`;

CREATE TABLE `trmeals_target_progress_detail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_meals_target_progress` bigint(20) unsigned NOT NULL,
  `targetable_id` bigint(20) unsigned DEFAULT NULL,
  `targetable_type` varchar(255) DEFAULT NULL,
  `level` varchar(255) NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `achievements` varchar(500) DEFAULT NULL,
  `progress` int(11) DEFAULT NULL,
  `persentase_complete` decimal(5,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `challenges` text DEFAULT NULL,
  `mitigation` text DEFAULT NULL,
  `risk` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmeals_target_progress_detail_id_meals_target_progress_foreign` (`id_meals_target_progress`),
  KEY `targetable_index` (`targetable_id`,`targetable_type`),
  CONSTRAINT `trmeals_target_progress_detail_id_meals_target_progress_foreign` FOREIGN KEY (`id_meals_target_progress`) REFERENCES `trmeals_target_progress` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmealsfrm` */

DROP TABLE IF EXISTS `trmealsfrm`;

CREATE TABLE `trmealsfrm` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `tanggalregistrasi` datetime NOT NULL,
  `umur` int(11) NOT NULL,
  `jeniskelamin` varchar(50) NOT NULL,
  `statuskomplain` varchar(50) NOT NULL,
  `notelp` varchar(20) NOT NULL,
  `alamat` varchar(500) NOT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT 0,
  `userpenerima_id` bigint(20) unsigned NOT NULL,
  `jabatanpenerima_id` bigint(20) unsigned NOT NULL,
  `notelppenerima` varchar(20) NOT NULL,
  `channels` varchar(100) NOT NULL,
  `channelslainnya` varchar(100) DEFAULT NULL,
  `kategorikomplain` varchar(100) NOT NULL,
  `deskripsikomplain` varchar(500) NOT NULL,
  `tanggalselesai` datetime DEFAULT NULL,
  `userhandler_id` bigint(20) unsigned NOT NULL,
  `jabatanhandler_id` bigint(20) unsigned NOT NULL,
  `notelphandler` varchar(20) NOT NULL,
  `deskripsi` varchar(500) NOT NULL,
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
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `programoutcomeoutputactivity_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `trainingname` varchar(200) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trmealspreposttestpeserta` */

DROP TABLE IF EXISTS `trmealspreposttestpeserta`;

CREATE TABLE `trmealspreposttestpeserta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `preposttest_id` bigint(20) unsigned NOT NULL,
  `dusun_id` bigint(20) unsigned NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jeniskelamin` varchar(50) NOT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `prescore` int(11) DEFAULT NULL,
  `filedbytraineepre` tinyint(1) NOT NULL DEFAULT 0,
  `postscore` int(11) DEFAULT NULL,
  `filedbytraineepost` tinyint(1) NOT NULL DEFAULT 0,
  `valuechange` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trmealspreposttestpeserta_preposttest_id_foreign` (`preposttest_id`),
  KEY `trmealspreposttestpeserta_dusun_id_foreign` (`dusun_id`),
  CONSTRAINT `trmealspreposttestpeserta_dusun_id_foreign` FOREIGN KEY (`dusun_id`) REFERENCES `dusun` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trmealspreposttestpeserta_preposttest_id_foreign` FOREIGN KEY (`preposttest_id`) REFERENCES `trmealspreposttest` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogram` */

DROP TABLE IF EXISTS `trprogram`;

CREATE TABLE `trprogram` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `tanggalmulai` datetime NOT NULL,
  `tanggalselesai` datetime NOT NULL,
  `totalnilai` decimal(20,2) DEFAULT NULL,
  `ekspektasipenerimamanfaat` int(11) DEFAULT NULL,
  `ekspektasipenerimamanfaatwoman` int(11) DEFAULT NULL,
  `ekspektasipenerimamanfaatman` int(11) DEFAULT NULL,
  `ekspektasipenerimamanfaatgirl` int(11) DEFAULT NULL,
  `ekspektasipenerimamanfaatboy` int(11) DEFAULT NULL,
  `ekspektasipenerimamanfaattidaklangsung` int(11) DEFAULT NULL,
  `deskripsiprojek` longtext DEFAULT NULL,
  `analisamasalah` longtext DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogram_user_id_foreign` (`user_id`),
  CONSTRAINT `trprogram_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramgoal` */

DROP TABLE IF EXISTS `trprogramgoal`;

CREATE TABLE `trprogramgoal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `deskripsi` longtext DEFAULT NULL,
  `indikator` longtext DEFAULT NULL,
  `target` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramgoal_program_id_foreign` (`program_id`),
  CONSTRAINT `trprogramgoal_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramkaitansdg` */

DROP TABLE IF EXISTS `trprogramkaitansdg`;

CREATE TABLE `trprogramkaitansdg` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `kaitansdg_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramkaitansdg_program_id_foreign` (`program_id`),
  KEY `trprogramkaitansdg_kaitansdg_id_foreign` (`kaitansdg_id`),
  CONSTRAINT `trprogramkaitansdg_kaitansdg_id_foreign` FOREIGN KEY (`kaitansdg_id`) REFERENCES `mkaitansdg` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramkaitansdg_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramkelompokmarjinal` */

DROP TABLE IF EXISTS `trprogramkelompokmarjinal`;

CREATE TABLE `trprogramkelompokmarjinal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `kelompokmarjinal_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramkelompokmarjinals_program_id_foreign` (`program_id`),
  KEY `trprogramkelompokmarjinals_kelompokmarjinal_id_foreign` (`kelompokmarjinal_id`),
  CONSTRAINT `trprogramkelompokmarjinals_kelompokmarjinal_id_foreign` FOREIGN KEY (`kelompokmarjinal_id`) REFERENCES `mkelompokmarjinal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramkelompokmarjinals_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramlokasi` */

DROP TABLE IF EXISTS `trprogramlokasi`;

CREATE TABLE `trprogramlokasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `provinsi_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramlokasi_program_id_foreign` (`program_id`),
  KEY `trprogramlokasi_provinsi_id_foreign` (`provinsi_id`),
  CONSTRAINT `trprogramlokasi_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramlokasi_provinsi_id_foreign` FOREIGN KEY (`provinsi_id`) REFERENCES `provinsi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramobjektif` */

DROP TABLE IF EXISTS `trprogramobjektif`;

CREATE TABLE `trprogramobjektif` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `deskripsi` longtext DEFAULT NULL,
  `indikator` longtext DEFAULT NULL,
  `target` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramobjektif_program_id_foreign` (`program_id`),
  CONSTRAINT `trprogramobjektif_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramoutcome` */

DROP TABLE IF EXISTS `trprogramoutcome`;

CREATE TABLE `trprogramoutcome` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `indikator` longtext DEFAULT NULL,
  `target` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deskripsi` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramoutcome_program_id_foreign` (`program_id`),
  CONSTRAINT `trprogramoutcome_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramoutcomeoutput` */

DROP TABLE IF EXISTS `trprogramoutcomeoutput`;

CREATE TABLE `trprogramoutcomeoutput` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `programoutcome_id` bigint(20) unsigned NOT NULL,
  `indikator` longtext DEFAULT NULL,
  `target` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deskripsi` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramoutcomeoutput_programoutcome_id_foreign` (`programoutcome_id`),
  CONSTRAINT `trprogramoutcomeoutput_programoutcome_id_foreign` FOREIGN KEY (`programoutcome_id`) REFERENCES `trprogramoutcome` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramoutcomeoutputactivity` */

DROP TABLE IF EXISTS `trprogramoutcomeoutputactivity`;

CREATE TABLE `trprogramoutcomeoutputactivity` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(500) DEFAULT NULL,
  `programoutcomeoutput_id` bigint(20) unsigned NOT NULL,
  `deskripsi` longtext DEFAULT NULL,
  `indikator` longtext DEFAULT NULL,
  `target` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramoutcomeoutputactivity_programoutcomeoutput_id_foreign` (`programoutcomeoutput_id`),
  CONSTRAINT `trprogramoutcomeoutputactivity_programoutcomeoutput_id_foreign` FOREIGN KEY (`programoutcomeoutput_id`) REFERENCES `trprogramoutcomeoutput` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogrampartner` */

DROP TABLE IF EXISTS `trprogrampartner`;

CREATE TABLE `trprogrampartner` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `partner_id` bigint(20) unsigned NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogrampartner_program_id_foreign` (`program_id`),
  KEY `trprogrampartner_partner_id_foreign` (`partner_id`),
  CONSTRAINT `trprogrampartner_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `mpartner` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogrampartner_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogrampendonor` */

DROP TABLE IF EXISTS `trprogrampendonor`;

CREATE TABLE `trprogrampendonor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `pendonor_id` bigint(20) unsigned NOT NULL,
  `nilaidonasi` decimal(20,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogrampendonor_program_id_foreign` (`program_id`),
  KEY `trprogrampendonor_pendonor_id_foreign` (`pendonor_id`),
  CONSTRAINT `trprogrampendonor_pendonor_id_foreign` FOREIGN KEY (`pendonor_id`) REFERENCES `mpendonor` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogrampendonor_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramreportschedule` */

DROP TABLE IF EXISTS `trprogramreportschedule`;

CREATE TABLE `trprogramreportschedule` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` varchar(500) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramreportschedule_program_id_foreign` (`program_id`),
  CONSTRAINT `trprogramreportschedule_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramtargetreinstra` */

DROP TABLE IF EXISTS `trprogramtargetreinstra`;

CREATE TABLE `trprogramtargetreinstra` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `targetreinstra_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramtargetreinstra_program_id_foreign` (`program_id`),
  KEY `trprogramtargetreinstra_targetreinstra_id_foreign` (`targetreinstra_id`),
  CONSTRAINT `trprogramtargetreinstra_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramtargetreinstra_targetreinstra_id_foreign` FOREIGN KEY (`targetreinstra_id`) REFERENCES `mtargetreinstra` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trprogramuser` */

DROP TABLE IF EXISTS `trprogramuser`;

CREATE TABLE `trprogramuser` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `peran_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trprogramuser_program_id_foreign` (`program_id`),
  KEY `trprogramuser_user_id_foreign` (`user_id`),
  KEY `trprogramuser_peran_id_foreign` (`peran_id`),
  CONSTRAINT `trprogramuser_peran_id_foreign` FOREIGN KEY (`peran_id`) REFERENCES `mperan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramuser_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `trprogram` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trprogramuser_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `username` varchar(200) DEFAULT NULL,
  `jabatan_id` bigint(20) unsigned DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_jabatan_id_foreign` (`jabatan_id`),
  CONSTRAINT `users_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `mjabatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
