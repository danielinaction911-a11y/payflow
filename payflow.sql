-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2026 at 02:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `payflow`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','suspended') NOT NULL DEFAULT 'active',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `status`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@gmail.com', '$2y$12$rEOYx.ug9f1RemmQmVi0eeJ0Adb5aD9L8.8lLcPWfKguk5ZeQUCVC', 'active', NULL, 'jzusRKEPp3gTvf9yjOk37nhBfQ1j1JwF0NmgxKBQSXCbSAkUVoxW3dJBhZGD', '2026-08-09 20:03:08', '2026-08-09 20:03:08'),
(2, 'Super Admin', 'admin@yourapp.com', '$2y$12$qH1niwkbvaaepTZKGSi5xeG9BP7csa0V8B.Mz2FLadCzpO.KxlUe.', 'active', NULL, NULL, '2026-08-09 20:07:11', '2026-08-09 20:07:11');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1786607743),
('5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1786607743;', 1786607743),
('ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4', 'i:1;', 1786507968),
('ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4:timer', 'i:1786507968;', 1786507968),
('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1786607624),
('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1786607624;', 1786607624),
('setting:default_currency', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:7;s:3:\"key\";s:16:\"default_currency\";s:5:\"value\";s:3:\"USD\";s:4:\"type\";s:6:\"select\";s:5:\"group\";s:7:\"general\";s:5:\"label\";s:16:\"Default Currency\";s:11:\"description\";s:37:\"Default currency shown platform-wide.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:7;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:7;s:3:\"key\";s:16:\"default_currency\";s:5:\"value\";s:3:\"USD\";s:4:\"type\";s:6:\"select\";s:5:\"group\";s:7:\"general\";s:5:\"label\";s:16:\"Default Currency\";s:11:\"description\";s:37:\"Default currency shown platform-wide.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:7;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101871954),
('setting:default_currency_symbol', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:8;s:3:\"key\";s:23:\"default_currency_symbol\";s:5:\"value\";s:1:\"$\";s:4:\"type\";s:4:\"text\";s:5:\"group\";s:7:\"general\";s:5:\"label\";s:23:\"Default Currency Symbol\";s:11:\"description\";s:32:\"Symbol for the default currency.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:7;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:8;s:3:\"key\";s:23:\"default_currency_symbol\";s:5:\"value\";s:1:\"$\";s:4:\"type\";s:4:\"text\";s:5:\"group\";s:7:\"general\";s:5:\"label\";s:23:\"Default Currency Symbol\";s:11:\"description\";s:32:\"Symbol for the default currency.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:7;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101871954),
('setting:default_theme', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:74;s:3:\"key\";s:13:\"default_theme\";s:5:\"value\";s:4:\"dark\";s:4:\"type\";s:6:\"select\";s:5:\"group\";s:10:\"appearance\";s:5:\"label\";s:13:\"Default Theme\";s:11:\"description\";s:35:\"Theme shown to first-time visitors.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:3;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:74;s:3:\"key\";s:13:\"default_theme\";s:5:\"value\";s:4:\"dark\";s:4:\"type\";s:6:\"select\";s:5:\"group\";s:10:\"appearance\";s:5:\"label\";s:13:\"Default Theme\";s:11:\"description\";s:35:\"Theme shown to first-time visitors.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:3;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101900162),
('setting:deposits_enabled', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:38;s:3:\"key\";s:16:\"deposits_enabled\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:7:\"finance\";s:5:\"label\";s:44:\"Whether deposits are enabled on the platform\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:11;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:38;s:3:\"key\";s:16:\"deposits_enabled\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:7:\"finance\";s:5:\"label\";s:44:\"Whether deposits are enabled on the platform\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:11;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101871338),
('setting:google_analytics_id', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:27;s:3:\"key\";s:19:\"google_analytics_id\";s:5:\"value\";N;s:4:\"type\";s:4:\"text\";s:5:\"group\";s:3:\"seo\";s:5:\"label\";s:19:\"Google Analytics ID\";s:11:\"description\";N;s:9:\"is_public\";i:0;s:10:\"sort_order\";i:5;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:27;s:3:\"key\";s:19:\"google_analytics_id\";s:5:\"value\";N;s:4:\"type\";s:4:\"text\";s:5:\"group\";s:3:\"seo\";s:5:\"label\";s:19:\"Google Analytics ID\";s:11:\"description\";N;s:9:\"is_public\";i:0;s:10:\"sort_order\";i:5;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101841360),
('setting:investments_enabled', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:43;s:3:\"key\";s:19:\"investments_enabled\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:7:\"finance\";s:5:\"label\";s:47:\"Whether investments are enabled on the platform\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:16;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:43;s:3:\"key\";s:19:\"investments_enabled\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:7:\"finance\";s:5:\"label\";s:47:\"Whether investments are enabled on the platform\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:16;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101939404),
('setting:referral_bonus_signup', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:67;s:3:\"key\";s:21:\"referral_bonus_signup\";s:5:\"value\";s:1:\"0\";s:4:\"type\";s:6:\"number\";s:5:\"group\";s:8:\"referral\";s:5:\"label\";s:19:\"Signup Bonus Amount\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:4;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:67;s:3:\"key\";s:21:\"referral_bonus_signup\";s:5:\"value\";s:1:\"0\";s:4:\"type\";s:6:\"number\";s:5:\"group\";s:8:\"referral\";s:5:\"label\";s:19:\"Signup Bonus Amount\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:4;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101861757),
('setting:require_kyc', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:47;s:3:\"key\";s:11:\"require_kyc\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:8:\"security\";s:5:\"label\";s:24:\"Require KYC Verification\";s:11:\"description\";s:55:\"Require users to complete KYC before withdrawing funds.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:1;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:47;s:3:\"key\";s:11:\"require_kyc\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:8:\"security\";s:5:\"label\";s:24:\"Require KYC Verification\";s:11:\"description\";s:55:\"Require users to complete KYC before withdrawing funds.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:1;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101870630),
('setting:seo_meta_description', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:24;s:3:\"key\";s:20:\"seo_meta_description\";s:5:\"value\";s:52:\"Trade, invest, and grow your portfolio with NexVest.\";s:4:\"type\";s:8:\"textarea\";s:5:\"group\";s:3:\"seo\";s:5:\"label\";s:16:\"Meta Description\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:2;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:24;s:3:\"key\";s:20:\"seo_meta_description\";s:5:\"value\";s:52:\"Trade, invest, and grow your portfolio with NexVest.\";s:4:\"type\";s:8:\"textarea\";s:5:\"group\";s:3:\"seo\";s:5:\"label\";s:16:\"Meta Description\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:2;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101841360),
('setting:seo_meta_keywords', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:25;s:3:\"key\";s:17:\"seo_meta_keywords\";s:5:\"value\";s:46:\"investment, crypto trading, fintech, portfolio\";s:4:\"type\";s:4:\"text\";s:5:\"group\";s:3:\"seo\";s:5:\"label\";s:13:\"Meta Keywords\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:3;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:25;s:3:\"key\";s:17:\"seo_meta_keywords\";s:5:\"value\";s:46:\"investment, crypto trading, fintech, portfolio\";s:4:\"type\";s:4:\"text\";s:5:\"group\";s:3:\"seo\";s:5:\"label\";s:13:\"Meta Keywords\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:3;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101841360),
('setting:seo_meta_title', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:23;s:3:\"key\";s:14:\"seo_meta_title\";s:5:\"value\";s:39:\"NexVest — Premium Investment Platform\";s:4:\"type\";s:4:\"text\";s:5:\"group\";s:3:\"seo\";s:5:\"label\";s:10:\"Meta Title\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:1;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:23;s:3:\"key\";s:14:\"seo_meta_title\";s:5:\"value\";s:39:\"NexVest — Premium Investment Platform\";s:4:\"type\";s:4:\"text\";s:5:\"group\";s:3:\"seo\";s:5:\"label\";s:10:\"Meta Title\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:1;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101841360),
('setting:seo_og_image', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:26;s:3:\"key\";s:12:\"seo_og_image\";s:5:\"value\";N;s:4:\"type\";s:5:\"image\";s:5:\"group\";s:3:\"seo\";s:5:\"label\";s:29:\"Social Share Image (OG Image)\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:4;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:26;s:3:\"key\";s:12:\"seo_og_image\";s:5:\"value\";N;s:4:\"type\";s:5:\"image\";s:5:\"group\";s:3:\"seo\";s:5:\"label\";s:29:\"Social Share Image (OG Image)\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:4;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101841360),
('setting:site_description', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:6;s:3:\"key\";s:16:\"site_description\";s:5:\"value\";s:50:\"A premium investment and digital banking platform.\";s:4:\"type\";s:8:\"textarea\";s:5:\"group\";s:7:\"general\";s:5:\"label\";s:16:\"Site Description\";s:11:\"description\";s:49:\"Used for SEO meta description and About sections.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:6;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:6;s:3:\"key\";s:16:\"site_description\";s:5:\"value\";s:50:\"A premium investment and digital banking platform.\";s:4:\"type\";s:8:\"textarea\";s:5:\"group\";s:7:\"general\";s:5:\"label\";s:16:\"Site Description\";s:11:\"description\";s:49:\"Used for SEO meta description and About sections.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:6;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101841360),
('setting:site_favicon', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:5;s:3:\"key\";s:12:\"site_favicon\";s:5:\"value\";N;s:4:\"type\";s:5:\"image\";s:5:\"group\";s:7:\"general\";s:5:\"label\";s:7:\"Favicon\";s:11:\"description\";s:33:\"Small icon shown in browser tabs.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:5;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:5;s:3:\"key\";s:12:\"site_favicon\";s:5:\"value\";N;s:4:\"type\";s:5:\"image\";s:5:\"group\";s:7:\"general\";s:5:\"label\";s:7:\"Favicon\";s:11:\"description\";s:33:\"Small icon shown in browser tabs.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:5;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101841360),
('setting:site_name', 'N;', 2101967859),
('setting:site_title', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:1;s:3:\"key\";s:10:\"site_title\";s:5:\"value\";s:7:\"NexVest\";s:4:\"type\";s:4:\"text\";s:5:\"group\";s:7:\"general\";s:5:\"label\";s:9:\"Site Name\";s:11:\"description\";s:59:\"The name of your platform, shown in header and browser tab.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:1;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:1;s:3:\"key\";s:10:\"site_title\";s:5:\"value\";s:7:\"NexVest\";s:4:\"type\";s:4:\"text\";s:5:\"group\";s:7:\"general\";s:5:\"label\";s:9:\"Site Name\";s:11:\"description\";s:59:\"The name of your platform, shown in header and browser tab.\";s:9:\"is_public\";i:1;s:10:\"sort_order\";i:1;s:10:\"created_at\";s:19:\"2026-08-06 03:33:30\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101841359),
('setting:trading_enabled', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:40;s:3:\"key\";s:15:\"trading_enabled\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:7:\"finance\";s:5:\"label\";s:42:\"Whether trading is enabled on the platform\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:13;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:40;s:3:\"key\";s:15:\"trading_enabled\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:7:\"finance\";s:5:\"label\";s:42:\"Whether trading is enabled on the platform\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:13;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101900598),
('setting:two_factor_authentication', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:52;s:3:\"key\";s:25:\"two_factor_authentication\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:8:\"security\";s:5:\"label\";s:32:\"Enable Two-Factor Authentication\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:6;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:52;s:3:\"key\";s:25:\"two_factor_authentication\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:8:\"security\";s:5:\"label\";s:32:\"Enable Two-Factor Authentication\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:6;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101902183),
('setting:wallets_enabled', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:41;s:3:\"key\";s:15:\"wallets_enabled\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:7:\"finance\";s:5:\"label\";s:43:\"Whether wallets are enabled on the platform\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:14;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:41;s:3:\"key\";s:15:\"wallets_enabled\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:7:\"finance\";s:5:\"label\";s:43:\"Whether wallets are enabled on the platform\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:14;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101872537),
('setting:withdrawals_enabled', 'O:18:\"App\\Models\\Setting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:39;s:3:\"key\";s:19:\"withdrawals_enabled\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:7:\"finance\";s:5:\"label\";s:47:\"Whether withdrawals are enabled on the platform\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:12;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:39;s:3:\"key\";s:19:\"withdrawals_enabled\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"boolean\";s:5:\"group\";s:7:\"finance\";s:5:\"label\";s:47:\"Whether withdrawals are enabled on the platform\";s:11:\"description\";N;s:9:\"is_public\";i:1;s:10:\"sort_order\";i:12;s:10:\"created_at\";s:19:\"2026-08-06 03:33:31\";s:10:\"updated_at\";s:19:\"2026-08-10 20:16:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_public\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:5:\"label\";i:5;s:11:\"description\";i:6;s:9:\"is_public\";i:7;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2101870630),
('ticker_method_pool', 'a:6:{i:0;s:13:\"Bank Transfer\";i:1;s:12:\"USDT (TRC20)\";i:2;s:12:\"USDT (ERC20)\";i:3;s:7:\"Bitcoin\";i:4;s:6:\"PayPal\";i:5;s:6:\"Skrill\";}', 1786667478),
('ticker_name_pool', 'a:24:{i:0;s:9:\"Joshua R.\";i:1;s:8:\"Daryl G.\";i:2;s:9:\"Hailey B.\";i:3;s:8:\"Ruben F.\";i:4;s:9:\"Javier C.\";i:5;s:10:\"Derrick R.\";i:6;s:8:\"Sofia P.\";i:7;s:9:\"Carter M.\";i:8;s:9:\"Isobel W.\";i:9;s:7:\"Paul R.\";i:10;s:8:\"April H.\";i:11;s:12:\"Gabrielle C.\";i:12;s:9:\"Meghan P.\";i:13;s:6:\"Lee L.\";i:14;s:10:\"Abigail B.\";i:15;s:9:\"Daniel R.\";i:16;s:6:\"Amy R.\";i:17;s:8:\"Steve M.\";i:18;s:10:\"Gilbert M.\";i:19;s:11:\"Isabelle K.\";i:20;s:10:\"Francis P.\";i:21;s:8:\"Tammy W.\";i:22;s:10:\"Matthew J.\";i:23;s:7:\"Jack A.\";}', 1786668375),
('ticker_pair_pool', 'a:5:{i:0;s:7:\"BTCUSDT\";i:1;s:8:\"DOGEUSDT\";i:2;s:7:\"ETHUSDT\";i:3;s:7:\"SOLUSDT\";i:4;s:7:\"XRPUSDT\";}', 1786667478),
('ticker_plan_pool', 'a:7:{i:0;s:12:\"Starter Plan\";i:1;s:11:\"Bronze Plan\";i:2;s:11:\"Silver Plan\";i:3;s:9:\"Gold Plan\";i:4;s:13:\"Platinum Plan\";i:5;s:14:\"VIP Elite Plan\";i:6;s:15:\"Quick Flip Plan\";}', 1786667477);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_logs`
--

CREATE TABLE `cron_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `processed` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `completed` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `skipped` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `failed` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `message` text DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cron_logs`
--

INSERT INTO `cron_logs` (`id`, `name`, `status`, `processed`, `completed`, `skipped`, `failed`, `message`, `started_at`, `finished_at`, `created_at`, `updated_at`) VALUES
(1, 'investments:process-profits', 'success', 1, 0, 0, 0, 'Processed 1 investment(s). Completed 0. Skipped 0. Failed 0.', '2026-08-08 15:02:47', '2026-08-08 15:02:47', '2026-08-08 14:56:57', '2026-08-08 15:02:47'),
(2, 'trading:sync-prices', 'success', 5, 5, 0, 0, 'Updated 5 pair(s). Skipped 0.', '2026-08-08 14:57:01', '2026-08-08 14:57:03', '2026-08-08 14:57:02', '2026-08-08 14:57:03');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `symbol` varchar(20) DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `network` varchar(255) DEFAULT NULL,
  `allow_deposit` tinyint(1) NOT NULL DEFAULT 1,
  `allow_withdrawal` tinyint(1) NOT NULL DEFAULT 1,
  `type` enum('fiat','crypto') NOT NULL,
  `coingecko_id` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `icon`, `symbol`, `code`, `network`, `allow_deposit`, `allow_withdrawal`, `type`, `coingecko_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', NULL, '$', 'USD', NULL, 1, 1, 'fiat', NULL, 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(2, 'Bitcoin', 'images/currency/bitcoin.png', '₿', 'BTC', 'Bitcoin', 1, 1, 'crypto', 'bitcoin', 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(3, 'Ethereum', NULL, 'Ξ', 'ETH', 'ERC20', 1, 1, 'crypto', 'ethereum', 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(4, 'Tether (TRC20)', NULL, '₮', 'USDT', 'TRC20', 1, 1, 'crypto', 'tether', 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(5, 'Tether (ERC20)', NULL, '₮', 'USDT', 'ERC20', 1, 1, 'crypto', 'tether', 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(6, 'BNB', NULL, 'BNB', 'BNB', 'BEP20', 1, 1, 'crypto', 'binancecoin', 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(7, 'Solana', NULL, 'SOL', 'SOL', 'Solana', 1, 1, 'crypto', 'solana', 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(8, 'Dogecoin', NULL, 'Ð', 'DOGE', 'Dogecoin', 1, 1, 'crypto', 'dogecoin', 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(9, 'Ripple', NULL, 'XRP', 'XRP', 'XRP Ledger', 1, 1, 'crypto', 'ripple', 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(10, 'Litecoin', NULL, 'Ł', 'LTC', 'Litecoin', 1, 1, 'crypto', 'litecoin', 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(11, 'Tron', NULL, 'TRX', 'TRX', 'Tron', 1, 1, 'crypto', 'tron', 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(12, 'USD Coin', NULL, 'USDC', 'USDC', 'ERC20', 1, 1, 'crypto', 'usd-coin', 'active', '2026-08-05 19:33:31', '2026-08-05 19:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) NOT NULL,
  `method` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','confirmed','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` varchar(255) DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposits`
--

INSERT INTO `deposits` (`id`, `user_id`, `amount`, `fee`, `currency`, `method`, `transaction_id`, `status`, `rejection_reason`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 5, 500.00, 0.00, 'USD', 'Crypto', 'DEP-FXWJVFAKGX', 'pending', NULL, '{\"tx_hash\":\"Hash\"}', '2026-08-07 16:05:19', '2026-08-07 16:05:19');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(1, 'How do I create an account?', 'Click the \"Sign Up\" button, enter your email, username, and password, then verify your email address to activate your account.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(2, 'How do I deposit funds into my account?', 'Go to the Deposit page from your dashboard, choose a payment method (bank transfer, debit/credit card, or crypto), enter the amount, and follow the on-screen instructions.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(3, 'How long does a deposit take to reflect in my balance?', 'Bank transfers and card deposits are typically processed within a few minutes to a few hours. Crypto deposits reflect after the required network confirmations.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(4, 'What is the minimum deposit amount?', 'The minimum deposit amount depends on your selected payment method and is displayed on the Deposit page before you confirm your transaction.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(5, 'How do I withdraw my funds?', 'Navigate to the Withdraw page, select your preferred withdrawal method, enter the amount, confirm with your transaction PIN, and submit your request for processing.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(6, 'How long do withdrawals take to process?', 'Withdrawal requests are typically reviewed and processed within 24 to 48 hours, depending on the withdrawal method and account verification status.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(7, 'Why is my withdrawal pending?', 'Withdrawals go through a manual security review before approval. This helps protect your account from unauthorized transactions.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(8, 'What is KYC and why do I need to complete it?', 'KYC (Know Your Customer) is an identity verification process required by financial regulations. Completing KYC unlocks higher deposit and withdrawal limits and keeps your account secure.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(9, 'How do I enable Two-Factor Authentication (2FA)?', 'Go to Privacy & Security in your account settings and follow the steps to enable 2FA using an authenticator app for an extra layer of account protection.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(10, 'How do investment plans work?', 'Each investment plan has a minimum and maximum investment amount, an expected return on investment (ROI), and a fixed duration. Once you invest, your returns accumulate according to the plan terms until it matures.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(11, 'Can I withdraw my investment before it matures?', 'This depends on the specific investment plan. Some plans allow early withdrawal with a fee, while others require the full duration to be completed. Check the plan details before investing.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(12, 'How do I send money to another user?', 'Go to Send Money, search for the recipient by username, email, or user ID, enter the amount and an optional note, then confirm the transfer.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(13, 'Can I send money to someone outside the platform?', 'No, the Send Money feature only supports transfers between registered users on the platform for security purposes.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(14, 'How does the referral program work?', 'Share your unique referral link or code with others. When they sign up and make transactions, you earn a commission based on the referral program rates shown on your Referral page.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(15, 'Is trading available on the platform?', 'Yes, you can trade supported assets directly from the Trade page, with options for market orders, limit orders, stop-loss, and take-profit settings.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(16, 'What fees does the platform charge?', 'Fees vary by transaction type, such as deposits, withdrawals, and trades. All applicable fees are clearly displayed before you confirm any transaction.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(17, 'Is my money and personal information safe?', 'Yes, the platform uses industry-standard encryption, two-factor authentication, and manual review processes to protect your funds and personal data.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(18, 'I forgot my password. How do I reset it?', 'Click \"Forgot Password\" on the login page, enter your registered email, and follow the reset link sent to your inbox.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(19, 'How do I contact customer support?', 'You can reach support through Live Chat, by submitting a support ticket from the Help & Support page, or by emailing our support team directly.', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(20, 'Can I use the platform on my mobile device?', 'Yes, the platform is fully responsive and optimized for mobile browsers, offering the same features and security as the desktop experience.', '2026-08-05 19:33:31', '2026-08-05 19:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `type` enum('auto','manual') NOT NULL DEFAULT 'manual',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `min_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `max_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fixed_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `percent_fee` decimal(5,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(255) NOT NULL DEFAULT 'USD',
  `credentials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`credentials`)),
  `payment_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_fields`)),
  `instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`instructions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `name`, `code`, `logo`, `type`, `status`, `min_amount`, `max_amount`, `fixed_fee`, `percent_fee`, `currency`, `credentials`, `payment_fields`, `instructions`, `created_at`, `updated_at`) VALUES
(1, 'Paystack', 'paystack', 'images/gateway/paystack.png', 'auto', 1, 100.00, 1000000.00, 0.00, 1.50, 'NGN', '{\"public_key\":\"pk_test_xxx\",\"secret_key\":\"sk_test_xxx\"}', NULL, '{\"title\":\"Pay with Paystack\",\"steps\":[\"Click deposit\",\"Complete payment on Paystack\",\"Wallet will be credited instantly\"]}', '2026-04-13 04:33:50', '2026-04-13 04:33:50'),
(2, 'Bank Transfer', 'bank', 'images/gateway/online-payment.png', 'manual', 1, 500.00, 5000000.00, 0.00, 0.00, 'NGN', NULL, '[{\"name\":\"account_name\",\"label\":\"Sender Name\",\"type\":\"text\",\"required\":true},{\"name\":\"transaction_id\",\"label\":\"Transaction Reference\",\"type\":\"text\",\"required\":true},{\"name\":\"proof\",\"label\":\"Upload Payment Proof\",\"type\":\"file\",\"required\":true}]', '{\"title\":\"Bank Transfer Instructions\",\"steps\":[\"Transfer to the account below\",\"Upload proof of payment\",\"Wait for admin approval\"],\"details\":{\"bank_name\":\"Access Bank\",\"account_name\":\"Fan Platform Ltd\",\"account_number\":\"1234567890\"}}', '2026-04-13 04:34:02', '2026-04-13 04:34:02'),
(3, 'Crypto', 'crypto', 'images/gateway/bitcoin.png', 'manual', 1, 50.00, 100000.00, 0.00, 0.00, 'USD', NULL, '[{\"name\":\"tx_hash\",\"label\":\"Transaction Hash\",\"type\":\"text\",\"required\":true}]', '{\"title\":\"Crypto Payment\",\"steps\":[\"Send crypto to wallet below\",\"Paste your transaction hash\",\"Wait for confirmation\"],\"details\":{\"wallet_address\":\"0xABC123XYZ\",\"network\":\"USDT (TRC20)\"}}', '2026-04-13 04:34:16', '2026-04-13 04:34:16'),
(4, 'Gift Card', 'giftcard', 'images/gateway/gift-card.png', 'manual', 1, 50.00, 5000.00, 0.00, 0.00, 'USD', NULL, '[{\"name\":\"card_type\",\"label\":\"Card Type (Amazon, iTunes, Steam)\",\"type\":\"text\",\"required\":true},{\"name\":\"card_amount\",\"label\":\"Card Amount\",\"type\":\"text\",\"required\":true},{\"name\":\"card_code\",\"label\":\"Card Code\",\"type\":\"textarea\",\"required\":true},{\"name\":\"receipt\",\"label\":\"Upload Receipt \\/ Image\",\"type\":\"file\",\"required\":true}]', '{\"title\":\"Gift Card Payment Instructions\",\"steps\":[\"Purchase a valid gift card\",\"Ensure the card is unused\",\"Enter card details below\",\"Upload proof of purchase\",\"Wait for verification\"],\"details\":{\"accepted_cards\":\"Amazon, iTunes, Steam, Google Play\",\"region\":\"US \\/ UK preferred\",\"processing_time\":\"1 - 24 hours\"}}', '2026-04-13 07:41:54', '2026-04-13 07:41:54'),
(5, 'CashApp', 'cashapp', 'images/gateway/cashapp.png', 'manual', 1, 10.00, 5000.00, 0.00, 0.00, 'USD', NULL, '[{\"name\":\"sender_tag\",\"label\":\"Your CashApp Tag\",\"type\":\"text\",\"required\":true},{\"name\":\"proof\",\"label\":\"Upload Proof\",\"type\":\"file\",\"required\":true}]', '{\"steps\":[\"Send payment to $YourTag\",\"Upload proof\",\"Wait for approval\"],\"details\":{\"cash_tag\":\"$YourTag\"}}', '2026-04-13 07:42:07', '2026-04-13 07:42:07'),
(6, 'PayPal Manual', 'paypal', 'images/gateway/paypal.png', 'manual', 1, 10.00, 5000.00, 0.00, 0.00, 'USD', NULL, '[{\"name\":\"sender_email\",\"label\":\"Your PayPal Email\",\"type\":\"text\",\"required\":true},{\"name\":\"transaction_id\",\"label\":\"Transaction ID\",\"type\":\"text\",\"required\":true},{\"name\":\"proof\",\"label\":\"Upload Payment Screenshot\",\"type\":\"file\",\"required\":true}]', '{\"title\":\"PayPal Payment Instructions\",\"steps\":[\"Send payment to our PayPal email\",\"Use Friends & Family (if allowed)\",\"Copy the transaction ID\",\"Upload payment proof\",\"Submit and wait for approval\"],\"details\":{\"paypal_email\":\"your-paypal@email.com\",\"currency\":\"USD\",\"processing_time\":\"5 mins - 6 hours\"}}', '2026-04-13 07:50:35', '2026-04-13 07:50:35'),
(7, 'Zelle', 'zelle', 'images/gateway/zelle.png', 'manual', 1, 10.00, 14440.00, 0.00, 0.00, 'USD', '[]', '[]', '{\"title\":\"Zelle Payment Instructions\",\"steps\":[\"Send payment to our Zelle email or phone number\",\"Ensure correct recipient before sending\",\"Take a screenshot of the payment\",\"Upload proof of payment\",\"Wait for admin approval\"],\"details\":{\"zelle_email\":\"your-email@bank.com\",\"zelle_phone\":\"+1234567890\",\"processing_time\":\"Instant - 2 hours\"}}', '2026-04-25 08:35:27', '2026-06-04 23:47:41'),
(8, 'Venmo', 'venmo', 'images/gateway/venmo.png', 'manual', 1, 30.00, 2340.00, 0.00, 0.00, 'USD', NULL, NULL, '{\"title\":\"Venmo Payment Instructions\",\"steps\":[\"Send payment to our Venmo username\",\"Do NOT select \'Goods & Services\'\",\"Take screenshot of payment\",\"Upload proof of payment\",\"Wait for confirmation\"],\"details\":{\"venmo_username\":\"@yourusername\",\"full_name\":\"Your Business Name\",\"processing_time\":\"Instant - 3 hours\"}}', '2026-04-25 08:35:35', '2026-04-25 08:35:40');

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `investment_plan_id` bigint(20) UNSIGNED NOT NULL,
  `amount_invested` decimal(15,2) NOT NULL,
  `roi_percentage` decimal(8,4) NOT NULL,
  `expected_total_return` decimal(15,2) NOT NULL,
  `total_paid_out` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','completed','cancelled') NOT NULL DEFAULT 'active',
  `starts_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ends_at` timestamp NULL DEFAULT NULL,
  `last_profit_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `user_id`, `investment_plan_id`, `amount_invested`, `roi_percentage`, `expected_total_return`, `total_paid_out`, `status`, `starts_at`, `ends_at`, `last_profit_at`, `created_at`, `updated_at`) VALUES
(2, 5, 8, 90.00, 1.5000, 99.45, 0.00, 'active', '2026-08-11 15:48:57', '2026-08-18 15:48:57', NULL, '2026-08-11 15:48:57', '2026-08-11 15:48:57'),
(3, 5, 14, 100.00, 12.0000, 112.00, 0.00, 'active', '2026-08-11 15:50:18', '2026-08-14 15:50:18', NULL, '2026-08-11 15:50:18', '2026-08-11 15:50:18');

-- --------------------------------------------------------

--
-- Table structure for table `investment_plans`
--

CREATE TABLE `investment_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `min_amount` decimal(15,2) NOT NULL,
  `max_amount` decimal(15,2) NOT NULL,
  `roi_percentage` decimal(5,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `roi_type` enum('daily','weekly','monthly','yearly','one_time') NOT NULL DEFAULT 'daily',
  `features` text DEFAULT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `capital_back` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investment_plans`
--

INSERT INTO `investment_plans` (`id`, `name`, `slug`, `description`, `min_amount`, `max_amount`, `roi_percentage`, `duration_days`, `roi_type`, `features`, `is_popular`, `capital_back`, `status`, `created_at`, `updated_at`) VALUES
(8, 'Starter Plan', 'starter-plan', 'Perfect for first-time investors looking to test the waters with a low-risk, short-term plan.', 50.00, 499.00, 1.50, 7, 'daily', '[\"1.5% daily returns\",\"7 days duration\",\"Capital returned at end of plan\",\"Instant activation\",\"24\\/7 support access\"]', 0, 1, 'active', '2026-08-11 15:37:18', '2026-08-11 15:37:18'),
(9, 'Bronze Plan', 'bronze-plan', 'A steady entry-level plan for investors ready to commit a little more for better returns.', 500.00, 1999.00, 2.00, 14, 'daily', '[\"2% daily returns\",\"14 days duration\",\"Capital returned at end of plan\",\"Priority email support\",\"Access to market signals\"]', 0, 1, 'active', '2026-08-11 15:37:18', '2026-08-11 15:37:18'),
(10, 'Silver Plan', 'silver-plan', 'Our most popular mid-tier plan, balancing solid returns with manageable investment duration.', 2000.00, 4999.00, 2.75, 21, 'daily', '[\"2.75% daily returns\",\"21 days duration\",\"Capital returned at end of plan\",\"Priority live chat support\",\"Access to trading signals\",\"Dedicated account manager\"]', 1, 1, 'active', '2026-08-11 15:37:18', '2026-08-11 15:37:18'),
(11, 'Gold Plan', 'gold-plan', 'Designed for serious investors seeking higher returns over a committed monthly cycle.', 5000.00, 14999.00, 35.00, 30, 'monthly', '[\"35% return at month end\",\"30 days duration\",\"Capital returned at end of plan\",\"Dedicated account manager\",\"Advanced trading signal access\",\"Early withdrawal option (fee applies)\"]', 0, 1, 'active', '2026-08-11 15:37:18', '2026-08-11 15:37:18'),
(12, 'Platinum Plan', 'platinum-plan', 'A high-yield plan built for experienced investors ready to commit larger capital.', 15000.00, 49999.00, 55.00, 30, 'monthly', '[\"55% return at month end\",\"30 days duration\",\"Capital returned at end of plan\",\"VIP account manager\",\"Real-time trading signal alerts\",\"Weekly portfolio review call\",\"Priority withdrawal processing\"]', 0, 1, 'active', '2026-08-11 15:37:18', '2026-08-11 15:37:18'),
(13, 'VIP Elite Plan', 'vip-elite-plan', 'Our top-tier plan for high-net-worth investors seeking maximum returns and white-glove service.', 50000.00, 400000.00, 80.00, 30, 'monthly', '[\"80% return at month end\",\"30 days duration\",\"Capital returned at end of plan\",\"Personal VIP account manager\",\"Real-time trading signal alerts\",\"Weekly 1-on-1 strategy call\",\"Instant withdrawal processing\",\"Exclusive access to new signal launches\"]', 0, 1, 'active', '2026-08-11 15:37:18', '2026-08-11 15:37:18'),
(14, 'Quick Flip Plan', 'quick-flip-plan', 'A short, one-time payout plan for investors who want fast turnaround without a daily accrual cycle.', 100.00, 999.00, 12.00, 3, 'one_time', '[\"12% one-time return\",\"3 days duration\",\"Capital returned at end of plan\",\"Fastest payout cycle available\"]', 0, 1, 'active', '2026-08-11 15:37:18', '2026-08-11 15:37:18');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kycs`
--

CREATE TABLE `kycs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(100) NOT NULL,
  `status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `required_fields` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kycs`
--

INSERT INTO `kycs` (`id`, `type`, `status`, `required_fields`, `created_at`, `updated_at`) VALUES
(5, 'Driver\'s License', 'enabled', '[{\"name\":\"full_name\",\"label\":\"Full Legal Name\",\"type\":\"text\",\"required\":true},{\"name\":\"license_number\",\"label\":\"License Number\",\"type\":\"text\",\"required\":true},{\"name\":\"front_image\",\"label\":\"Front of License\",\"type\":\"file\",\"required\":true},{\"name\":\"back_image\",\"label\":\"Back of License\",\"type\":\"file\",\"required\":true}]', '2026-08-11 20:09:58', '2026-08-11 20:09:58'),
(6, 'National ID Card', 'enabled', '[{\"name\":\"full_name\",\"label\":\"Full Legal Name\",\"type\":\"text\",\"required\":true},{\"name\":\"id_number\",\"label\":\"ID Number\",\"type\":\"text\",\"required\":true},{\"name\":\"front_image\",\"label\":\"Front of ID Card\",\"type\":\"file\",\"required\":true},{\"name\":\"back_image\",\"label\":\"Back of ID Card\",\"type\":\"file\",\"required\":true}]', '2026-08-11 20:09:58', '2026-08-11 20:09:58'),
(7, 'Passport', 'enabled', '[{\"name\":\"full_name\",\"label\":\"Full Legal Name\",\"type\":\"text\",\"required\":true},{\"name\":\"passport_number\",\"label\":\"Passport Number\",\"type\":\"text\",\"required\":true},{\"name\":\"expiry_date\",\"label\":\"Expiry Date\",\"type\":\"text\",\"required\":true},{\"name\":\"passport_image\",\"label\":\"Passport Photo Page\",\"type\":\"file\",\"required\":true}]', '2026-08-11 20:09:58', '2026-08-11 20:09:58'),
(8, 'Proof of Address', 'enabled', '[{\"name\":\"document_type\",\"label\":\"Document Type (utility bill, bank statement)\",\"type\":\"text\",\"required\":true},{\"name\":\"document_image\",\"label\":\"Upload Document\",\"type\":\"file\",\"required\":true}]', '2026-08-11 20:09:58', '2026-08-11 20:09:58');

-- --------------------------------------------------------

--
-- Table structure for table `kyc_documents`
--

CREATE TABLE `kyc_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kyc_id` bigint(20) UNSIGNED NOT NULL,
  `required_fields` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kyc_documents`
--

INSERT INTO `kyc_documents` (`id`, `user_id`, `kyc_id`, `required_fields`, `status`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(2, 5, 8, '{\"document_type\":\"this is a testing ducment\",\"document_image\":\"images\\/kyc\\/kyc_5_document_image_6a7bf2899b954.jpg\"}', 'approved', NULL, '2026-08-11 20:11:53', '2026-08-11 20:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `login_activities`
--

CREATE TABLE `login_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `device` varchar(255) DEFAULT NULL,
  `device_type` varchar(300) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `successful` tinyint(1) NOT NULL DEFAULT 1,
  `logged_in_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_activities`
--

INSERT INTO `login_activities` (`id`, `user_id`, `ip_address`, `device`, `device_type`, `location`, `successful`, `logged_in_at`, `created_at`, `updated_at`) VALUES
(1, 5, '127.0.0.1', 'Windows · Chrome', 'tablet', NULL, 1, '2026-08-13 14:58:04', '2026-08-09 03:16:36', '2026-08-13 14:58:04'),
(2, 5, '127.0.0.1', 'Windows · Chrome', 'mobile', NULL, 1, '2026-08-09 03:16:36', '2026-08-09 03:16:36', '2026-08-09 03:16:36'),
(8, 5, '172.20.10.3', 'iOS · Safari', NULL, NULL, 1, '2026-08-12 15:16:43', '2026-08-12 15:16:43', '2026-08-12 15:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `mail_templates`
--

CREATE TABLE `mail_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mail_templates`
--

INSERT INTO `mail_templates` (`id`, `name`, `subject`, `body`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'withdraw_approved', 'Withdrawal Successful - {{ $amount }}', '<div style=\"margin:0;padding:0;background:#f4f6f9;font-family:\'Segoe UI\', Arial, sans-serif;\">\r\n\r\n    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:30px 0;\">\r\n        <tr>\r\n            <td align=\"center\">\r\n\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);\">\r\n\r\n                    <tr>\r\n                        <td style=\"background:#2563eb;padding:25px;text-align:center;color:#fff;\">\r\n                            <h1 style=\"margin:0;font-size:22px;\">Withdrawal Approved</h1>\r\n                            <p style=\"margin:6px 0 0;font-size:13px;opacity:0.9;\">Your withdrawal has been successfully processed</p>\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <tr>\r\n                        <td style=\"padding:30px 25px;color:#333;\">\r\n\r\n                            <h2 style=\"margin-top:0;font-size:20px;color:#2c3e50;\">Hello {{ $name }},</h2>\r\n\r\n                            <p style=\"font-size:15px;color:#555;line-height:1.6;\">\r\n                                Good news! Your withdrawal request has been approved and processed successfully.\r\n                            </p>\r\n\r\n                            <div style=\"margin:20px 0;padding:18px;background:#eff6ff;border-left:5px solid #2563eb;border-radius:8px;text-align:center;\">\r\n                                <p style=\"margin:0;font-size:13px;color:#888;\">AMOUNT WITHDRAWN</p>\r\n                                <p style=\"margin:6px 0 0;font-size:22px;font-weight:bold;color:#2563eb;\">{{ $amount }}</p>\r\n                            </div>\r\n\r\n                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:10px;font-size:14px;\">\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Reference</strong></td>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $reference }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Date</strong></td>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $date }}</td>\r\n                                </tr>\r\n                            </table>\r\n\r\n                            <p style=\"margin-top:20px;font-size:14px;color:#666;line-height:1.6;\">\r\n                                You can check your account for the updated balance and transaction record.\r\n                            </p>\r\n\r\n                            <div style=\"margin-top:25px;text-align:center;\">\r\n                                <a href=\"{{ $dashboard_url ?? \'#\' }}\" style=\"display:inline-block;padding:12px 22px;background:#2563eb;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;\">View Account</a>\r\n                            </div>\r\n\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <tr>\r\n                        <td style=\"background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;\">\r\n                            <p style=\"margin:0;\">This transaction was processed by <strong>{{ config(\'app.name\') }}</strong></p>\r\n                            <p style=\"margin:5px 0 0;\">© {{ date(\'Y\') }} All rights reserved</p>\r\n                        </td>\r\n                    </tr>\r\n\r\n                </table>\r\n\r\n            </td>\r\n        </tr>\r\n    </table>\r\n\r\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(2, 'withdraw_failed', 'Withdrawal Failed - Action Required', '<div style=\"margin:0;padding:0;background:#fef2f2;font-family:\'Segoe UI\', Arial, sans-serif;\">\r\n\r\n    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:30px 0;\">\r\n        <tr>\r\n            <td align=\"center\">\r\n\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);\">\r\n\r\n                    <tr>\r\n                        <td style=\"background:#dc2626;padding:25px;text-align:center;color:#fff;\">\r\n                            <h1 style=\"margin:0;font-size:22px;\">Withdrawal Failed</h1>\r\n                            <p style=\"margin:6px 0 0;font-size:13px;opacity:0.9;\">Your withdrawal could not be processed</p>\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <tr>\r\n                        <td style=\"padding:30px 25px;color:#333;\">\r\n\r\n                            <h2 style=\"margin-top:0;font-size:20px;color:#2c3e50;\">Hello {{ $name }},</h2>\r\n\r\n                            <p style=\"font-size:15px;color:#555;line-height:1.6;\">\r\n                                Unfortunately, your withdrawal request could not be completed.\r\n                            </p>\r\n\r\n                            <div style=\"margin:20px 0;padding:18px;background:#fef2f2;border-left:5px solid #dc2626;border-radius:8px;text-align:center;\">\r\n                                <p style=\"margin:0;font-size:13px;color:#888;\">AMOUNT REQUESTED</p>\r\n                                <p style=\"margin:6px 0 0;font-size:22px;font-weight:bold;color:#dc2626;\">{{ $amount }}</p>\r\n                            </div>\r\n\r\n                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:10px;font-size:14px;\">\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Reference</strong></td>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $reference }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Reason</strong></td>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $reason ?? \'Unknown error\' }}</td>\r\n                                </tr>\r\n                            </table>\r\n\r\n                            <p style=\"margin-top:20px;font-size:14px;color:#666;line-height:1.6;\">\r\n                                Please try again or contact support if you need help.\r\n                            </p>\r\n\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <tr>\r\n                        <td style=\"background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;\">\r\n                            <p style=\"margin:0;\">This transaction was processed by <strong>{{ config(\'app.name\') }}</strong></p>\r\n                            <p style=\"margin:5px 0 0;\">© {{ date(\'Y\') }} All rights reserved</p>\r\n                        </td>\r\n                    </tr>\r\n\r\n                </table>\r\n\r\n            </td>\r\n        </tr>\r\n    </table>\r\n\r\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(3, 'investment_profit_added', 'New Profit Added - {{ $profit_amount }}', '<div style=\"margin:0;padding:0;background:#f4f6f9;font-family:\'Segoe UI\', Arial, sans-serif;\">\r\n\r\n    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:30px 0;\">\r\n        <tr>\r\n            <td align=\"center\">\r\n\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);\">\r\n\r\n                    <tr>\r\n                        <td style=\"background:#16a34a;padding:25px;text-align:center;color:#fff;\">\r\n                            <h1 style=\"margin:0;font-size:22px;\">Profit Added</h1>\r\n                            <p style=\"margin:6px 0 0;font-size:13px;opacity:0.9;\">A new profit has been credited to your investment</p>\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <tr>\r\n                        <td style=\"padding:30px 25px;color:#333;\">\r\n\r\n                            <h2 style=\"margin-top:0;font-size:20px;color:#2c3e50;\">Hello {{ $name }},</h2>\r\n\r\n                            <p style=\"font-size:15px;color:#555;line-height:1.6;\">\r\n                                We have added a new profit record to your investment account.\r\n                            </p>\r\n\r\n                            <div style=\"margin:20px 0;padding:18px;background:#f0fdf4;border-left:5px solid #16a34a;border-radius:8px;text-align:center;\">\r\n                                <p style=\"margin:0;font-size:13px;color:#888;\">PROFIT AMOUNT</p>\r\n                                <p style=\"margin:6px 0 0;font-size:22px;font-weight:bold;color:#16a34a;\">{{ $profit_amount }}</p>\r\n                            </div>\r\n\r\n                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:10px;font-size:14px;\">\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Investment</strong></td>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $investment_name ?? \'Investment\' }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Percentage</strong></td>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $percentage ?? \'N/A\' }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Source</strong></td>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $source ?? \'System\' }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Description</strong></td>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $description ?? \'Profit added\' }}</td>\r\n                                </tr>\r\n                            </table>\r\n\r\n                            <p style=\"margin-top:20px;font-size:14px;color:#666;line-height:1.6;\">\r\n                                You can review this update in your account dashboard.\r\n                            </p>\r\n\r\n                            <div style=\"margin-top:25px;text-align:center;\">\r\n                                <a href=\"{{ $dashboard_url ?? \'#\' }}\" style=\"display:inline-block;padding:12px 22px;background:#16a34a;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;\">View Account</a>\r\n                            </div>\r\n\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <tr>\r\n                        <td style=\"background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;\">\r\n                            <p style=\"margin:0;\">This update was processed by <strong>{{ config(\'app.name\') }}</strong></p>\r\n                            <p style=\"margin:5px 0 0;\">© {{ date(\'Y\') }} All rights reserved</p>\r\n                        </td>\r\n                    </tr>\r\n\r\n                </table>\r\n\r\n            </td>\r\n        </tr>\r\n    </table>\r\n\r\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(4, 'kyc_approved', 'KYC Approved - Verification Successful', '<div style=\"margin:0;padding:0;background:#f4f7fb;font-family:Segoe UI,Arial,sans-serif;\">\r\n\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:40px 15px;\">\r\n<tr>\r\n<td align=\"center\">\r\n\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:650px;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.08);\">\r\n\r\n<tr>\r\n<td style=\"background:linear-gradient(135deg,#16a34a,#22c55e);padding:35px 30px;text-align:center;color:#fff;\">\r\n\r\n<h1 style=\"margin:0;font-size:28px;font-weight:700;\">KYC Approved</h1>\r\n\r\n<p style=\"margin:10px 0 0;font-size:15px;opacity:.9;\">Your identity verification has been successfully approved</p>\r\n\r\n</td>\r\n</tr>\r\n\r\n<tr>\r\n<td style=\"padding:40px 35px;\">\r\n\r\n<h2 style=\"margin-top:0;color:#1e293b;font-size:22px;\">Hello {{ $name }},</h2>\r\n\r\n<p style=\"font-size:15px;line-height:1.8;color:#475569;\">\r\nWe are pleased to inform you that your KYC submission has been reviewed and approved.\r\n</p>\r\n\r\n<div style=\"margin-top:30px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:25px;\">\r\n\r\n<h3 style=\"margin-top:0;color:#0f172a;font-size:18px;\">Verification Details</h3>\r\n\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:15px;\">\r\n<tr>\r\n<td style=\"padding:10px 0;color:#64748b;font-size:14px;\">Status</td>\r\n<td align=\"right\" style=\"padding:10px 0;\">\r\n<span style=\"background:#dcfce7;color:#166534;padding:6px 14px;border-radius:50px;font-size:12px;font-weight:700;\">APPROVED</span>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding:10px 0;color:#64748b;font-size:14px;\">Date</td>\r\n<td align=\"right\" style=\"padding:10px 0;color:#0f172a;font-weight:700;font-size:15px;\">{{ $date }}</td>\r\n</tr>\r\n</table>\r\n\r\n</div>\r\n\r\n<div style=\"margin-top:35px;text-align:center;\">\r\n\r\n<a href=\"{{ $dashboard_url }}\" style=\"display:inline-block;background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;padding:14px 30px;text-decoration:none;border-radius:10px;font-size:15px;font-weight:600;\">View Dashboard</a>\r\n\r\n</div>\r\n\r\n<p style=\"margin-top:35px;font-size:14px;line-height:1.7;color:#64748b;\">\r\nThank you for completing your verification. If you have any questions, please contact support.\r\n</p>\r\n\r\n</td>\r\n</tr>\r\n\r\n<tr>\r\n<td style=\"background:#f8fafc;padding:25px;text-align:center;border-top:1px solid #e2e8f0;\">\r\n\r\n<p style=\"margin:0;color:#94a3b8;font-size:13px;\">© {{ date(\"Y\") }} {{ config(\"app.name\") }}. All rights reserved.</p>\r\n\r\n</td>\r\n</tr>\r\n\r\n</table>\r\n\r\n</td>\r\n</tr>\r\n</table>\r\n\r\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(5, 'kyc_rejected', 'KYC Rejected - Action Required', '<div style=\"margin:0;padding:0;background:#fef2f2;font-family:Segoe UI,Arial,sans-serif;\">\r\n\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:40px 15px;\">\r\n<tr>\r\n<td align=\"center\">\r\n\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:650px;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.08);\">\r\n\r\n<tr>\r\n<td style=\"background:linear-gradient(135deg,#dc2626,#ef4444);padding:35px 30px;text-align:center;color:#fff;\">\r\n\r\n<h1 style=\"margin:0;font-size:28px;font-weight:700;\">KYC Rejected</h1>\r\n\r\n<p style=\"margin:10px 0 0;font-size:15px;opacity:.9;\">Your identity verification needs attention</p>\r\n\r\n</td>\r\n</tr>\r\n\r\n<tr>\r\n<td style=\"padding:40px 35px;\">\r\n\r\n<h2 style=\"margin-top:0;color:#1e293b;font-size:22px;\">Hello {{ $name }},</h2>\r\n\r\n<p style=\"font-size:15px;line-height:1.8;color:#475569;\">\r\nUnfortunately, your KYC submission was not approved.\r\n</p>\r\n\r\n<div style=\"margin-top:30px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:25px;\">\r\n\r\n<h3 style=\"margin-top:0;color:#0f172a;font-size:18px;\">Review Details</h3>\r\n\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:15px;\">\r\n<tr>\r\n<td style=\"padding:10px 0;color:#64748b;font-size:14px;\">Status</td>\r\n<td align=\"right\" style=\"padding:10px 0;\">\r\n<span style=\"background:#fee2e2;color:#991b1b;padding:6px 14px;border-radius:50px;font-size:12px;font-weight:700;\">REJECTED</span>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding:10px 0;color:#64748b;font-size:14px;\">Reason</td>\r\n<td align=\"right\" style=\"padding:10px 0;color:#0f172a;font-weight:700;font-size:15px;\">{{ $reason ?? \'Not specified\' }}</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding:10px 0;color:#64748b;font-size:14px;\">Date</td>\r\n<td align=\"right\" style=\"padding:10px 0;color:#0f172a;font-weight:700;font-size:15px;\">{{ $date }}</td>\r\n</tr>\r\n</table>\r\n\r\n</div>\r\n\r\n<div style=\"margin-top:35px;text-align:center;\">\r\n\r\n<a href=\"{{ $dashboard_url }}\" style=\"display:inline-block;background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;padding:14px 30px;text-decoration:none;border-radius:10px;font-size:15px;font-weight:600;\">View Dashboard</a>\r\n\r\n</div>\r\n\r\n<p style=\"margin-top:35px;font-size:14px;line-height:1.7;color:#64748b;\">\r\nPlease review the reason above and update your KYC submission if needed.\r\n</p>\r\n\r\n</td>\r\n</tr>\r\n\r\n<tr>\r\n<td style=\"background:#fff1f2;padding:25px;text-align:center;border-top:1px solid #e2e8f0;\">\r\n\r\n<p style=\"margin:0;color:#94a3b8;font-size:13px;\">© {{ date(\"Y\") }} {{ config(\"app.name\") }}. All rights reserved.</p>\r\n\r\n</td>\r\n</tr>\r\n\r\n</table>\r\n\r\n</td>\r\n</tr>\r\n</table>\r\n\r\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(6, 'subscriber_notification', '{{ $subject ?? \"Notification\" }}', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n    <meta charset=\"utf-8\">\r\n    <title>{{ config(\'app.name\') }}</title>\r\n</head>\r\n<body style=\"margin:0; padding:0; background:#f4f6f9; font-family:Arial, Helvetica, sans-serif;\">\r\n\r\n    <div style=\"padding:40px 20px;\">\r\n        <div style=\"max-width:600px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.08);\">\r\n\r\n            <div style=\"background:#2563eb; padding:30px; text-align:center;\">\r\n                <h1 style=\"margin:0; color:#ffffff; font-size:24px;\">\r\n                    {{ config(\'app.name\') }}\r\n                </h1>\r\n            </div>\r\n\r\n            <div style=\"padding:35px; color:#374151; line-height:1.8;\">\r\n\r\n                <h2 style=\"margin-top:0; color:#111827; font-size:22px;\">\r\n                    Hello {{ $name }},\r\n                </h2>\r\n\r\n                <div style=\"font-size:15px;\">\r\n                    {!! nl2br(e($message)) !!}\r\n                </div>\r\n\r\n                <div style=\"margin-top:30px;\">\r\n                    <p style=\"margin:0;\">Best regards,</p>\r\n                    <p style=\"margin:5px 0 0; font-weight:bold; color:#111827;\">{{ config(\'app.name\') }} Team</p>\r\n                </div>\r\n            </div>\r\n\r\n            <div style=\"height:1px; background:#e5e7eb;\"></div>\r\n\r\n            <div style=\"padding:25px; background:#f9fafb; text-align:center;\">\r\n                <p style=\"margin:0; color:#6b7280; font-size:13px;\">This email was sent by {{ config(\'app.name\') }}.</p>\r\n                <p style=\"margin:8px 0 0; color:#9ca3af; font-size:12px;\">© {{ date(\'Y\') }} {{ config(\'app.name\') }}. All rights reserved.</p>\r\n            </div>\r\n\r\n        </div>\r\n    </div>\r\n\r\n</body>\r\n</html>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(7, 'balance_updated', 'Balance Updated - {{ $amount }}', '<div style=\"margin:0;padding:0;background:#f4f6f9;font-family:\'Segoe UI\', Arial, sans-serif;\">\r\n\r\n    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:30px 0;\">\r\n        <tr>\r\n            <td align=\"center\">\r\n\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);\">\r\n\r\n                    <tr>\r\n                        <td style=\"background:{{ $action === \'add\' ? \'#16a34a\' : \'#dc2626\' }};padding:25px;text-align:center;color:#fff;\">\r\n                            <h1 style=\"margin:0;font-size:22px;\">Balance {{ $action === \'add\' ? \'Credited\' : \'Debited\' }}</h1>\r\n                            <p style=\"margin:6px 0 0;font-size:13px;opacity:0.9;\">\r\n                                Your {{ $wallet }} balance was {{ $action_label ?? \'updated\' }} successfully\r\n                            </p>\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <tr>\r\n                        <td style=\"padding:30px 25px;color:#333;\">\r\n\r\n                            <h2 style=\"margin-top:0;font-size:20px;color:#2c3e50;\">\r\n                                Hello {{ $name }},\r\n                            </h2>\r\n\r\n                            <p style=\"font-size:15px;color:#555;line-height:1.6;\">\r\n                                Your account balance has been {{ $action_label ?? \'updated\' }} by the admin team.\r\n                            </p>\r\n\r\n                            <div style=\"margin:20px 0;padding:18px;background:#f8fafc;border-left:5px solid {{ $action === \'add\' ? \'#16a34a\' : \'#dc2626\' }};border-radius:8px;text-align:center;\">\r\n                                <p style=\"margin:0;font-size:13px;color:#888;\">AMOUNT</p>\r\n                                <p style=\"margin:6px 0 0;font-size:22px;font-weight:bold;color:{{ $action === \'add\' ? \'#16a34a\' : \'#dc2626\' }};\">\r\n                                    {{ $action === \'add\' ? \'+\' : \'-\' }}{{ $amount }}\r\n                                </p>\r\n                            </div>\r\n\r\n                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:10px;font-size:14px;\">\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Wallet</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $wallet }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Reference</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $reference }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>New Balance</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $balance }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Note</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $remark ?? \'Admin balance update\' }}</td>\r\n                                </tr>\r\n                            </table>\r\n\r\n                            <div style=\"margin-top:25px;text-align:center;\">\r\n                                <a href=\"{{ $dashboard_url ?? \'#\' }}\" style=\"display:inline-block;padding:12px 22px;background:#2563eb;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;\">View Dashboard</a>\r\n                            </div>\r\n\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <tr>\r\n                        <td style=\"background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;\">\r\n                            <p style=\"margin:0;\">This update was processed by <strong>{{ config(\'app.name\') }}</strong></p>\r\n                            <p style=\"margin:5px 0 0;\">© {{ date(\'Y\') }} All rights reserved</p>\r\n                        </td>\r\n                    </tr>\r\n\r\n                </table>\r\n\r\n            </td>\r\n        </tr>\r\n    </table>\r\n\r\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(8, 'email_verification', 'Verify Your Email Address', '<div style=\"margin:0;padding:0;background:#f4f6f9;font-family:\'Segoe UI\', Arial, sans-serif;\">\r\n    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:30px 0;\">\r\n        <tr>\r\n            <td align=\"center\">\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);\">\r\n                    <tr>\r\n                        <td style=\"background:#2563eb;padding:25px;text-align:center;color:#fff;\">\r\n                            <h1 style=\"margin:0;font-size:22px;\">Verify Your Email</h1>\r\n                            <p style=\"margin:6px 0 0;font-size:13px;opacity:0.9;\">Confirm your email address to activate your account</p>\r\n                        </td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td style=\"padding:30px 25px;color:#333;\">\r\n                            <h2 style=\"margin-top:0;font-size:20px;color:#2c3e50;\">Hello {{ $name }},</h2>\r\n                            <p style=\"font-size:15px;color:#555;line-height:1.6;\">Thanks for registering with us. Please verify your email address by clicking the button below.</p>\r\n                            <div style=\"margin-top:25px;text-align:center;\">\r\n                                <a href=\"{{ $verification_url }}\" style=\"display:inline-block;padding:12px 22px;background:#2563eb;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;\">Verify Email Address</a>\r\n                            </div>\r\n                            <p style=\"margin-top:20px;font-size:14px;color:#666;line-height:1.6;\">If you did not create this account, you can safely ignore this email.</p>\r\n                        </td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td style=\"background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;\">\r\n                            <p style=\"margin:0;\">This email was sent by <strong>{{ config(\'app.name\') }}</strong></p>\r\n                            <p style=\"margin:5px 0 0;\">© {{ date(\'Y\') }} All rights reserved</p>\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </table>\r\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(9, 'account_created', 'Welcome to {{ config(\"app.name\") }} 🎉', '<div style=\"margin:0;padding:0;background:#f4f6f9;font-family:\'Segoe UI\', Arial, sans-serif;\">\r\n    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:30px 0;\">\r\n        <tr>\r\n            <td align=\"center\">\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);\">\r\n                    <tr>\r\n                        <td style=\"background:#16a34a;padding:25px;text-align:center;color:#fff;\">\r\n                            <h1 style=\"margin:0;font-size:22px;\">🎉 Welcome Aboard!</h1>\r\n                            <p style=\"margin:6px 0 0;font-size:13px;opacity:0.9;\">Your account has been successfully created</p>\r\n                        </td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td style=\"padding:30px 25px;color:#333;\">\r\n                            <h2 style=\"margin-top:0;font-size:20px;color:#2c3e50;\">Hello {{ $name }},</h2>\r\n                            <p style=\"font-size:15px;color:#555;line-height:1.6;\">We’re excited to have you on board! Your account has been created successfully. You can now start exploring and enjoying all the features available on our platform.</p>\r\n                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:10px;font-size:14px;\">\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Email</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $email }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Username</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $username ?? \'-\' }}</td>\r\n                                </tr>\r\n                            </table>\r\n                            <div style=\"margin-top:25px;text-align:center;\">\r\n                                <a href=\"{{ $login_url ?? route(\'login\') }}\" style=\"display:inline-block;padding:12px 22px;background:#16a34a;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;\">Login to Your Account</a>\r\n                            </div>\r\n                            <p style=\"margin-top:20px;font-size:14px;color:#666;line-height:1.6;\">If you did not create this account, please contact our support team immediately.</p>\r\n                        </td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td style=\"background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;\">\r\n                            <p style=\"margin:0;\">© {{ date(\'Y\') }} <strong>{{ config(\'app.name\') }}</strong>. All rights reserved.</p>\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </table>\r\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(10, 'referral_bonus', 'Referral Bonus Credited - {{ $amount }}', '<div style=\"margin:0;padding:0;background:#f4f6f9;font-family:\'Segoe UI\', Arial, sans-serif;\">\r\n    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:30px 0;\">\r\n        <tr>\r\n            <td align=\"center\">\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);\">\r\n                    <tr>\r\n                        <td style=\"background:#0f766e;padding:25px;text-align:center;color:#fff;\">\r\n                            <h1 style=\"margin:0;font-size:22px;\">Referral Bonus Added</h1>\r\n                            <p style=\"margin:6px 0 0;font-size:13px;opacity:0.9;\">Your referral reward has been credited</p>\r\n                        </td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td style=\"padding:30px 25px;color:#333;\">\r\n                            <h2 style=\"margin-top:0;font-size:20px;color:#2c3e50;\">Hello {{ $name }},</h2>\r\n                            <p style=\"font-size:15px;color:#555;line-height:1.6;\">Thank you for referring a new user. Your referral bonus has been added to your balance.</p>\r\n                            <div style=\"margin:20px 0;padding:18px;background:#ecfeff;border-left:5px solid #0f766e;border-radius:8px;text-align:center;\">\r\n                                <p style=\"margin:0;font-size:13px;color:#888;\">BONUS CREDITED</p>\r\n                                <p style=\"margin:6px 0 0;font-size:22px;font-weight:bold;color:#0f766e;\">{{ $amount }}</p>\r\n                            </div>\r\n                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:10px;font-size:14px;\">\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Referred User</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $referred_user_email }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>New Balance</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $new_balance }}</td>\r\n                                </tr>\r\n                            </table>\r\n                            <div style=\"margin-top:25px;text-align:center;\">\r\n                                <a href=\"{{ $dashboard_url ?? \'#\' }}\" style=\"display:inline-block;padding:12px 22px;background:#0f766e;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;\">View Account</a>\r\n                            </div>\r\n                        </td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td style=\"background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;\">\r\n                            <p style=\"margin:0;\">This update was processed by <strong>{{ config(\'app.name\') }}</strong></p>\r\n                            <p style=\"margin:5px 0 0;\">© {{ date(\'Y\') }} All rights reserved</p>\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </table>\r\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(11, 'investment_created', 'Investment Created - {{ $amount }}', '<div style=\"margin:0;padding:0;background:#f4f6f9;font-family:\'Segoe UI\', Arial, sans-serif;\">\r\n    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:30px 0;\">\r\n        <tr>\r\n            <td align=\"center\">\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);\">\r\n                    <tr>\r\n                        <td style=\"background:#1d4ed8;padding:25px;text-align:center;color:#fff;\">\r\n                            <h1 style=\"margin:0;font-size:22px;\">Investment Started</h1>\r\n                            <p style=\"margin:6px 0 0;font-size:13px;opacity:0.9;\">Your investment has been created successfully</p>\r\n                        </td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td style=\"padding:30px 25px;color:#333;\">\r\n                            <h2 style=\"margin-top:0;font-size:20px;color:#2c3e50;\">Hello {{ $name }},</h2>\r\n                            <p style=\"font-size:15px;color:#555;line-height:1.6;\">Your investment in <strong>{{ $plan_name }}</strong> is now active. We’ve saved the details below for your records.</p>\r\n\r\n                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:10px;font-size:14px;\">\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Plan</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $plan_name }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Amount</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $amount }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Expected Profit</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $expected_profit }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Maturity Date</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $maturity_date }}</td>\r\n                                </tr>\r\n                                <tr>\r\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Reference</strong></td>\r\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $reference }}</td>\r\n                                </tr>\r\n                            </table>\r\n\r\n                            <div style=\"margin-top:25px;text-align:center;\">\r\n                                <a href=\"{{ $dashboard_url ?? \'#\' }}\" style=\"display:inline-block;padding:12px 22px;background:#1d4ed8;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;\">View Investments</a>\r\n                            </div>\r\n\r\n                            <p style=\"margin-top:20px;font-size:14px;color:#666;line-height:1.6;\">You will also receive updates as your investment progresses, depending on your notification settings.</p>\r\n                        </td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td style=\"background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;\">\r\n                            <p style=\"margin:0;\">This email was sent by <strong>{{ config(\'app.name\') }}</strong></p>\r\n                            <p style=\"margin:5px 0 0;\">© {{ date(\'Y\') }} All rights reserved</p>\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </table>\r\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(12, 'password_reset_link', 'Password Reset Request', '<div style=\"margin:0;padding:0;background:#f4f6f9;font-family:\'Segoe UI\', Arial, sans-serif;\">\r\n    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:30px 0;\">\r\n        <tr>\r\n            <td align=\"center\">\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);\">\r\n                    <tr>\r\n                        <td style=\"background:#7c3aed;padding:25px;text-align:center;color:#fff;\">\r\n                            <h1 style=\"margin:0;font-size:22px;\">Reset Your Password</h1>\r\n                            <p style=\"margin:6px 0 0;font-size:13px;opacity:0.9;\">Use the link below to create a new password</p>\r\n                        </td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td style=\"padding:30px 25px;color:#333;\">\r\n                            <h2 style=\"margin-top:0;font-size:20px;color:#2c3e50;\">Hello {{ $name }},</h2>\r\n                            <p style=\"font-size:15px;color:#555;line-height:1.6;\">We received a request to reset your password. Click the button below to continue.</p>\r\n                            <div style=\"margin-top:25px;text-align:center;\">\r\n                                <a href=\"{{ $reset_url }}\" style=\"display:inline-block;padding:12px 22px;background:#7c3aed;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;\">Reset Password</a>\r\n                            </div>\r\n                            <p style=\"margin-top:20px;font-size:14px;color:#666;line-height:1.6;\">If you did not request a password reset, you can safely ignore this email.</p>\r\n                        </td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td style=\"background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;\">\r\n                            <p style=\"margin:0;\">This email was sent by <strong>{{ config(\'app.name\') }}</strong></p>\r\n                            <p style=\"margin:5px 0 0;\">© {{ date(\'Y\') }} All rights reserved</p>\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </table>\r\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(13, 'new_ip_login_alert', 'New Login Detected From {{ $current_ip }}', '<div style=\"margin:0;padding:0;background:#f4f6f9;font-family:\'Segoe UI\', Arial, sans-serif;\">\n    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"padding:30px 0;\">\n        <tr>\n            <td align=\"center\">\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);\">\n                    <tr>\n                        <td style=\"background:#dc2626;padding:25px;text-align:center;color:#fff;\">\n                            <h1 style=\"margin:0;font-size:22px;\">New Login Alert</h1>\n                            <p style=\"margin:6px 0 0;font-size:13px;opacity:0.9;\">A login was detected from a new IP address</p>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td style=\"padding:30px 25px;color:#333;\">\n                            <h2 style=\"margin-top:0;font-size:20px;color:#2c3e50;\">Hello {{ $name }},</h2>\n                            <p style=\"font-size:15px;color:#555;line-height:1.6;\">We noticed a login to your account from a new IP address. If this was you, you can ignore this message. If not, please review your account security immediately.</p>\n\n                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:18px;font-size:14px;\">\n                                <tr>\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Current IP</strong></td>\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $current_ip }}</td>\n                                </tr>\n                                <tr>\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Previous IP</strong></td>\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $previous_ip }}</td>\n                                </tr>\n                                <tr>\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Device</strong></td>\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $device_type }}</td>\n                                </tr>\n                                <tr>\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Location</strong></td>\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $city }}, {{ $country }}</td>\n                                </tr>\n                                <tr>\n                                    <td style=\"padding:10px 0;border-bottom:1px solid #eee;color:#555;\"><strong>Login Time</strong></td>\n                                    <td align=\"right\" style=\"padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;\">{{ $login_time }}</td>\n                                </tr>\n                            </table>\n\n                            <div style=\"margin-top:25px;text-align:center;\">\n                                <a href=\"{{ $dashboard_url ?? \'#\' }}\" style=\"display:inline-block;padding:12px 22px;background:#dc2626;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;\">Open Dashboard</a>\n                            </div>\n\n                            <p style=\"margin-top:20px;font-size:14px;color:#666;line-height:1.6;\">For better security, consider changing your password if you do not recognize this activity.</p>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td style=\"background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;\">\n                            <p style=\"margin:0;\">This alert was sent by <strong>{{ config(\'app.name\') }}</strong></p>\n                            <p style=\"margin:5px 0 0;\">© {{ date(\'Y\') }} All rights reserved</p>\n                        </td>\n                    </tr>\n                </table>\n            </td>\n        </tr>\n    </table>\n</div>', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_05_235236_add_two_factor_columns_to_users_table', 1),
(5, '2026_08_06_000705_create_kycs_table', 1),
(6, '2026_08_06_000706_create_kyc_documents_table', 1),
(7, '2026_08_06_000707_create_investment_plans_table', 1),
(8, '2026_08_06_000708_create_investments_table', 1),
(9, '2026_08_06_000710_create_profit_logs_table', 1),
(10, '2026_08_06_000711_create_currencies_table', 1),
(11, '2026_08_06_000756_create_wallets_table', 1),
(12, '2026_08_06_000757_create_deposits_table', 1),
(13, '2026_08_06_000758_create_transfers_table', 1),
(14, '2026_08_06_000758_create_withdrawals_table', 1),
(15, '2026_08_06_000759_create_money_requests_table', 1),
(16, '2026_08_06_000800_create_transactions_table', 1),
(17, '2026_08_06_000801_create_trading_pairs_table', 1),
(18, '2026_08_06_000802_create_trades_table', 1),
(19, '2026_08_06_000803_create_referrals_table', 1),
(20, '2026_08_06_000804_create_referral_commissions_table', 1),
(21, '2026_08_06_000805_create_support_tickets_table', 1),
(22, '2026_08_06_000806_create_faqs_table', 1),
(23, '2026_08_06_000806_create_ticket_replies_table', 1),
(24, '2026_08_06_000807_create_notifications_table', 1),
(25, '2026_08_06_000808_create_login_activities_table', 1),
(26, '2026_08_06_000809_create_security_alerts_table', 1),
(27, '2026_08_06_000810_create_settings_table', 1),
(28, '2026_08_06_011517_create_gateways_table', 1),
(29, '2026_08_06_011656_create_cron_logs_table', 1),
(30, '2026_08_06_011808_create_mail_templates_table', 1),
(31, '2026_08_06_012153_create_admins_table', 1),
(32, '2026_08_06_013409_create_withdraw_gateways_table', 1),
(33, '2026_08_06_022203_create_policies_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `money_requests`
--

CREATE TABLE `money_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requester_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(24,8) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','accepted','declined','expired') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `money_requests`
--

INSERT INTO `money_requests` (`id`, `requester_id`, `recipient_id`, `amount`, `message`, `expires_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 232.00000000, 'this is just a test', '2026-08-16 15:07:02', 'declined', '2026-08-09 15:07:02', '2026-08-09 15:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `image` text DEFAULT NULL,
  `type` enum('info','warning','error','success') NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `body`, `image`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 'New money request', 'emmanuel sunday requested $232.00 from you.', NULL, 'info', 1, '2026-08-09 15:07:02', '2026-08-09 15:08:01'),
(2, 5, 'Money request declined', 'sunday daniel declined your request for $232.00.', NULL, 'warning', 0, '2026-08-09 15:11:29', '2026-08-09 15:11:29'),
(3, 5, 'Money request declined', 'sunday daniel declined your request for $232.00.', 'images/tickets/reply_admin_6a7be40569f4e.jpeg', 'warning', 0, '2026-08-09 15:14:29', '2026-08-09 15:14:29'),
(4, 5, 'Profit added', 'A profit of $100.00 has been added to your investment in Starter Plan.', NULL, 'success', 0, '2026-08-10 13:57:38', '2026-08-10 13:57:38'),
(5, 5, 'Balance adjusted', 'Your account was debited $77.090000000000003411. Description', NULL, 'info', 0, '2026-08-10 15:11:07', '2026-08-10 15:11:07'),
(7, 5, 'Balance credited', 'Your account was credited $200.00. this is a test', NULL, 'success', 0, '2026-08-10 15:39:06', '2026-08-10 15:39:06'),
(8, 5, 'Ticket resolved', 'Your ticket \"subject\" has been marked as resolved.', NULL, 'success', 0, '2026-08-11 02:22:20', '2026-08-11 02:22:20'),
(10, 5, 'New reply on your ticket', 'Support replied to \"subject\".', NULL, 'info', 0, '2026-08-11 19:09:57', '2026-08-11 19:09:57'),
(11, 5, 'Identity verified', 'Your Proof of Address submission has been approved. Your account is now verified.', NULL, 'success', 0, '2026-08-11 20:14:48', '2026-08-11 20:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'general',
  `content` longtext NOT NULL,
  `version` varchar(255) NOT NULL DEFAULT '1.0',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `effective_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `policies`
--

INSERT INTO `policies` (`id`, `title`, `slug`, `type`, `content`, `version`, `is_active`, `sort_order`, `effective_date`, `created_at`, `updated_at`) VALUES
(1, 'Terms of Service', 'terms-of-service', 'terms', '<h2>1. Acceptance of Terms</h2>\n<p>By accessing or using this platform, you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, you must not use our services.</p>\n\n<h2>2. Eligibility</h2>\n<p>You must be at least 18 years old and legally capable of entering into binding contracts to use this platform. By registering, you confirm that you meet these requirements.</p>\n\n<h2>3. Account Responsibilities</h2>\n<p>You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. Notify us immediately of any unauthorized use.</p>\n\n<h2>4. Platform Use</h2>\n<p>You agree to use the platform only for lawful purposes and in accordance with these Terms. Any fraudulent, abusive, or illegal activity may result in immediate account suspension.</p>\n\n<h2>5. Investment and Trading Risks</h2>\n<p>All investments and trades carried out on this platform involve risk. Past performance does not guarantee future results. Please review our Risk Disclosure for full details.</p>\n\n<h2>6. Fees</h2>\n<p>Applicable fees for deposits, withdrawals, trades, and other transactions are disclosed before you confirm any action. Fees may be updated from time to time.</p>\n\n<h2>7. Termination</h2>\n<p>We reserve the right to suspend or terminate your account at our discretion if these Terms are violated or if required by law.</p>\n\n<h2>8. Changes to Terms</h2>\n<p>We may update these Terms periodically. Continued use of the platform after changes take effect constitutes acceptance of the revised Terms.</p>\n\n<h2>9. Contact</h2>\n<p>For questions regarding these Terms, please contact our support team.</p>', '1.0', 1, 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(2, 'Privacy Policy', 'privacy-policy', 'privacy', '<h2>1. Information We Collect</h2>\r\n<p>We collect information you provide directly, such as your name, email, phone number, address, and identity documents submitted for KYC verification, as well as data generated through your use of the platform, including transaction history and device information.</p>\r\n\r\n<h2>2. How We Use Your Information</h2>\r\n<p>Your information is used to provide and improve our services, process transactions, verify your identity, communicate with you, prevent fraud, and comply with legal obligations.</p>\r\n\r\n<h2>3. Information Sharing</h2>\r\n<p>We do not sell your personal information. We may share data with trusted service providers, payment processors, and regulatory authorities as required to operate the platform and comply with applicable laws.</p>\r\n\r\n<h2>4. Data Security</h2>\r\n<p>We implement industry-standard security measures, including encryption and access controls, to protect your personal information from unauthorized access, alteration, or disclosure.</p>\r\n\r\n<h2>5. Data Retention</h2>\r\n<p>We retain your information for as long as necessary to provide our services and to comply with legal, regulatory, and accounting requirements.</p>\r\n\r\n<h2>6. Your Rights</h2>\r\n<p>You may request access to, correction of, or deletion of your personal data, subject to applicable law and our regulatory recordkeeping obligations.</p>\r\n\r\n<h2>7. Cookies</h2>\r\n<p>We use cookies to enhance your experience on the platform. See our Cookie Policy for more details.</p>\r\n\r\n<h2>8. Changes to This Policy</h2>\r\n<p>We may revise this Privacy Policy periodically. Updates will be posted on this page with a new effective date.</p>', '1.0', 1, 2, '2026-08-05 19:33:31', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(3, 'Anti-Money Laundering (AML) Policy', 'aml-policy', 'aml', '<h2>1. Our Commitment</h2>\r\n<p>We are committed to preventing the platform from being used for money laundering, terrorist financing, or any other illicit financial activity, in accordance with applicable laws and regulations.</p>\r\n\r\n<h2>2. Customer Due Diligence</h2>\r\n<p>All users are required to complete identity verification (KYC) before performing certain transactions. Additional due diligence may be requested for high-value transactions or suspicious activity.</p>\r\n\r\n<h2>3. Transaction Monitoring</h2>\r\n<p>We monitor account activity and transactions for patterns that may indicate money laundering or other financial crimes, using both automated systems and manual review.</p>\r\n\r\n<h2>4. Reporting Obligations</h2>\r\n<p>Where required by law, we report suspicious activity to relevant regulatory and law enforcement authorities. We may be required to freeze or restrict accounts under investigation.</p>\r\n\r\n<h2>5. Record Keeping</h2>\r\n<p>We maintain records of customer identification and transaction history in accordance with applicable regulatory retention requirements.</p>\r\n\r\n<h2>6. User Responsibilities</h2>\r\n<p>Users must provide accurate information during registration and verification, and must not use the platform to facilitate illegal financial activity of any kind.</p>', '1.0', 1, 3, '2026-08-05 19:33:31', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(4, 'KYC (Know Your Customer) Policy', 'kyc-policy', 'kyc', '<h2>1. Purpose of KYC</h2>\r\n<p>Know Your Customer (KYC) verification helps us confirm the identity of our users, prevent fraud, and comply with regulatory requirements.</p>\r\n\r\n<h2>2. Required Documentation</h2>\r\n<p>Users may be required to submit a government-issued ID (passport, national ID, or driver\'s license) and proof of address to complete verification.</p>\r\n\r\n<h2>3. Verification Levels</h2>\r\n<p>Certain platform features, including higher withdrawal limits, may only be unlocked once KYC verification has been successfully completed and approved.</p>\r\n\r\n<h2>4. Review Process</h2>\r\n<p>Submitted documents are reviewed by our compliance team. Verification status will be updated on your account, and you will be notified of approval or rejection, including reasons for any rejection.</p>\r\n\r\n<h2>5. Data Handling</h2>\r\n<p>Documents submitted for KYC purposes are stored securely and used solely for identity verification and regulatory compliance, in accordance with our Privacy Policy.</p>\r\n\r\n<h2>6. Re-Verification</h2>\r\n<p>We may periodically request updated documentation to ensure your account information remains accurate and compliant with applicable regulations.</p>', '1.0', 1, 4, '2026-08-05 19:33:31', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(5, 'Risk Disclosure', 'risk-disclosure', 'risk', '<h2>1. General Risk Warning</h2>\r\n<p>Investing and trading involve substantial risk, including the potential loss of some or all of your invested capital. You should not invest funds you cannot afford to lose.</p>\r\n\r\n<h2>2. Market Volatility</h2>\r\n<p>The value of investments and assets, including cryptocurrencies, can fluctuate significantly and unpredictably due to market conditions beyond our control.</p>\r\n\r\n<h2>3. No Guaranteed Returns</h2>\r\n<p>Expected returns displayed on investment plans are estimates based on historical or projected performance and are not guaranteed. Past performance is not indicative of future results.</p>\r\n\r\n<h2>4. Platform Risk</h2>\r\n<p>While we implement strong security measures, no platform can guarantee complete protection against technical failures, cyberattacks, or third-party service disruptions.</p>\r\n\r\n<h2>5. Your Responsibility</h2>\r\n<p>You are solely responsible for evaluating the risks associated with any investment or trading decision made on this platform. We recommend consulting an independent financial advisor before investing.</p>', '1.0', 1, 5, '2026-08-05 19:33:31', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(6, 'Cookie Policy', 'cookie-policy', 'cookie', '<h2>1. What Are Cookies</h2>\r\n<p>Cookies are small text files stored on your device that help us recognize your browser, remember preferences, and improve your experience on the platform.</p>\r\n\r\n<h2>2. Types of Cookies We Use</h2>\r\n<p>We use essential cookies required for core functionality (such as staying logged in), performance cookies to analyze site usage, and preference cookies to remember settings like theme and language.</p>\r\n\r\n<h2>3. Managing Cookies</h2>\r\n<p>You can control or disable cookies through your browser settings. Please note that disabling essential cookies may affect platform functionality.</p>\r\n\r\n<h2>4. Third-Party Cookies</h2>\r\n<p>Some cookies may be set by third-party services we use for analytics or security purposes. These providers have their own privacy and cookie policies.</p>\r\n\r\n<h2>5. Updates to This Policy</h2>\r\n<p>We may update this Cookie Policy from time to time. Continued use of the platform indicates acceptance of the updated policy.</p>', '1.0', 1, 6, '2026-08-05 19:33:31', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(7, 'Refund Policy', 'refund-policy', 'refund', '<h2>1. General Policy</h2>\r\n<p>Due to the nature of financial transactions, deposits, investments, and completed trades are generally non-refundable once processed.</p>\r\n\r\n<h2>2. Erroneous Transactions</h2>\r\n<p>If you believe a transaction was processed in error, please contact support immediately. We will investigate and, where appropriate, issue a correction or refund.</p>\r\n\r\n<h2>3. Failed Transactions</h2>\r\n<p>If a deposit or withdrawal fails due to a technical error on our end, the amount will be refunded or corrected within a reasonable timeframe after investigation.</p>\r\n\r\n<h2>4. Investment Plans</h2>\r\n<p>Refunds or early exits from investment plans are subject to the specific terms of each plan, which are disclosed before you confirm your investment.</p>\r\n\r\n<h2>5. Processing Time</h2>\r\n<p>Approved refunds are processed back to the original payment method or platform wallet within the timeframes disclosed at the time of approval.</p>', '1.0', 1, 7, '2026-08-05 19:33:31', '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(8, 'Accessibility Statement', 'accessibility', 'accessibility', '<h2>1. Our Commitment</h2>\r\n<p>We are committed to ensuring our platform is accessible to all users, including those with disabilities, and strive to meet recognized web accessibility standards.</p>\r\n\r\n<h2>2. Accessibility Features</h2>\r\n<p>Our platform is designed with readable typography, sufficient color contrast, keyboard navigation support, and responsive layouts across devices.</p>\r\n\r\n<h2>3. Ongoing Improvements</h2>\r\n<p>We continuously review and improve the accessibility of our platform as part of our regular design and development process.</p>\r\n\r\n<h2>4. Feedback</h2>\r\n<p>If you experience any difficulty accessing content or features on our platform, please contact our support team so we can address the issue.</p>', '1.0', 1, 8, '2026-08-05 19:33:31', '2026-08-05 19:33:31', '2026-08-05 19:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `profit_logs`
--

CREATE TABLE `profit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `investment_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(28,18) NOT NULL,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `referrer_id` bigint(20) UNSIGNED NOT NULL,
  `referred_id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_commissions`
--

CREATE TABLE `referral_commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `referral_id` bigint(20) UNSIGNED NOT NULL,
  `source_transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(18,2) NOT NULL,
  `status` enum('pending','paid') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security_alerts`
--

CREATE TABLE `security_alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `security_alerts`
--

INSERT INTO `security_alerts` (`id`, `user_id`, `type`, `description`, `resolved_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'pin_changed', 'Your transaction PIN was changed.', NULL, '2026-08-08 17:31:30', '2026-08-08 17:31:30'),
(2, 5, 'password_changed', 'Your account password was changed.', NULL, '2026-08-08 17:41:23', '2026-08-08 17:41:23'),
(3, 1, 'password_changed', 'Your account password was changed.', '2026-08-10 12:46:05', '2026-08-08 17:49:04', '2026-08-10 12:46:05'),
(4, 5, 'device_logged_out', 'A session from iOS · Safari (172.20.10.3) was remotely logged out.', NULL, '2026-08-09 15:47:29', '2026-08-09 15:47:29'),
(5, 5, 'device_logged_out', 'A session from iOS · Safari (172.20.10.3) was remotely logged out.', NULL, '2026-08-09 15:47:50', '2026-08-09 15:47:50'),
(6, 5, 'device_logged_out', 'A session from iOS · Safari (172.20.10.3) was remotely logged out.', NULL, '2026-08-12 05:59:51', '2026-08-12 05:59:51'),
(7, 5, 'device_logged_out', 'A session from iOS · Safari (172.20.10.3) was remotely logged out.', NULL, '2026-08-12 06:02:17', '2026-08-12 06:02:17');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('MQE92NRZZ6QqBlmN8JZRGK2u7a2K57tB7guozCJC', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZjdLcHFxb0syQUNpZW1DMGV6TEZ1cDhZUkdib2JDVGd3c1l5Tkh4MiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbm90aWZpY2F0aW9ucyI7czo1OiJyb3V0ZSI7czoxOToibm90aWZpY2F0aW9ucy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==', 1786666862),
('nsm5Fllg6RPahnpAirzloCukJp0Ng6j0zrM5i8e7', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiUWpxSGF4b1NHamJ5blhkeEZKY0o4bzRIZ1drdE5JY1YxVk9lcjM5VSI7czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTk6InBhc3N3b3JkX2hhc2hfYWRtaW4iO3M6NjQ6ImM4NDY0MmJjODRjOThjMDZlMDNiMzJlOTQ1MDMzMzMwMGZjZGYzMGVlMzY4ZTljMDA3NTczNTE2OTgxYTY4MWEiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjUxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2VjdXJlLXBhbmVsL2ludmVzdG1lbnQtcGxhbnMiO3M6NToicm91dGUiO3M6NDc6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5pbnZlc3RtZW50LXBsYW5zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', 1786667115);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `label` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `label`, `description`, `is_public`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'site_title', 'NexVest', 'text', 'general', 'Site Name', 'The name of your platform, shown in header and browser tab.', 1, 1, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(2, 'site_tagline', 'Invest Smarter, Grow Faster', 'text', 'general', 'Site Tagline', 'Short tagline shown near the logo or on the landing page.', 1, 2, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(3, 'site_logo', NULL, 'image', 'general', 'Site Logo (Light Mode)', 'Main logo shown in light theme.', 1, 3, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(4, 'site_logo_dark', NULL, 'image', 'general', 'Site Logo (Dark Mode)', 'Logo variant shown in dark theme.', 1, 4, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(5, 'site_favicon', 'images/settings/site_favicon_1786607690.png', 'image', 'general', 'Favicon', 'Small icon shown in browser tabs.', 1, 5, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(6, 'site_description', 'A premium investment and digital banking platform.', 'textarea', 'general', 'Site Description', 'Used for SEO meta description and About sections.', 1, 6, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(7, 'default_currency', 'USD', 'select', 'general', 'Default Currency', 'Default currency shown platform-wide.', 1, 7, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(8, 'default_currency_symbol', '$', 'text', 'general', 'Default Currency Symbol', 'Symbol for the default currency.', 1, 7, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(9, 'default_timezone', 'UTC', 'select', 'general', 'Default Timezone', 'Default timezone for new accounts.', 1, 8, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(10, 'default_language', 'en', 'select', 'general', 'Default Language', 'Default language for new accounts.', 1, 9, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(11, 'maintenance_mode', '0', 'boolean', 'general', 'Maintenance Mode', 'Temporarily disable public access to the platform.', 0, 10, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(12, 'contact_email', 'support@nexvest.com', 'email', 'contact', 'Support Email', 'Primary support email address.', 1, 1, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(13, 'contact_phone', '+1 234 567 8900', 'text', 'contact', 'Support Phone', 'Displayed on contact/support pages.', 1, 2, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(14, 'contact_address', NULL, 'textarea', 'contact', 'Company Address', 'Physical/registered address shown in footer.', 1, 3, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(15, 'live_chat_enabled', '1', 'boolean', 'contact', 'Enable Live Chat', 'Show live chat widget on Help & Support page.', 1, 4, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(16, 'chat_plugin_script', NULL, 'textarea', 'contact', 'Chat Plugin Script', 'Custom script for integrating third-party chat plugins.', 1, 5, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(17, 'social_twitter', NULL, 'url', 'social', 'Twitter / X URL', NULL, 1, 1, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(18, 'social_facebook', NULL, 'url', 'social', 'Facebook URL', NULL, 1, 2, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(19, 'social_instagram', NULL, 'url', 'social', 'Instagram URL', NULL, 1, 3, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(20, 'social_linkedin', NULL, 'url', 'social', 'LinkedIn URL', NULL, 1, 4, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(21, 'social_telegram', NULL, 'url', 'social', 'Telegram URL', NULL, 1, 5, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(22, 'social_youtube', NULL, 'url', 'social', 'YouTube URL', NULL, 1, 6, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(23, 'seo_meta_title', 'NexVest — Premium Investment Platform', 'text', 'seo', 'Meta Title', NULL, 1, 1, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(24, 'seo_meta_description', 'Trade, invest, and grow your portfolio with NexVest.', 'textarea', 'seo', 'Meta Description', NULL, 1, 2, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(25, 'seo_meta_keywords', 'investment, crypto trading, fintech, portfolio', 'text', 'seo', 'Meta Keywords', NULL, 1, 3, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(26, 'seo_og_image', NULL, 'image', 'seo', 'Social Share Image (OG Image)', NULL, 1, 4, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(27, 'google_analytics_id', NULL, 'text', 'seo', 'Google Analytics ID', NULL, 0, 5, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(28, 'min_deposit_amount', '10', 'number', 'finance', 'Minimum Deposit Amount', NULL, 1, 1, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(29, 'max_deposit_amount', '100000', 'number', 'finance', 'Maximum Deposit Amount', NULL, 1, 2, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(30, 'min_withdrawal_amount', '20', 'number', 'finance', 'Minimum Withdrawal Amount', NULL, 1, 3, '2026-08-05 19:33:30', '2026-08-13 16:04:20'),
(31, 'max_withdrawal_amount', '50000', 'number', 'finance', 'Maximum Withdrawal Amount', NULL, 1, 4, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(32, 'deposit_fee_percentage', '0', 'number', 'finance', 'Deposit Fee (%)', NULL, 1, 5, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(33, 'withdrawal_fee_percentage', '2', 'number', 'finance', 'Withdrawal Fee (%)', NULL, 1, 6, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(34, 'transfer_fee_percentage', '0', 'number', 'finance', 'Internal Transfer Fee (%)', NULL, 1, 7, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(35, 'withdrawal_processing_time', '24-48 hours', 'text', 'finance', 'Withdrawal Processing Time', NULL, 1, 8, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(36, 'auto_approve_deposits', '0', 'boolean', 'finance', 'Auto-Approve Deposits', 'If disabled, all deposits require manual admin approval.', 0, 9, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(37, 'auto_approve_withdrawals', '0', 'boolean', 'finance', 'Auto-Approve Withdrawals', NULL, 0, 10, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(38, 'deposits_enabled', '1', 'boolean', 'finance', 'Whether deposits are enabled on the platform', NULL, 1, 11, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(39, 'withdrawals_enabled', '1', 'boolean', 'finance', 'Whether withdrawals are enabled on the platform', NULL, 1, 12, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(40, 'trading_enabled', '1', 'boolean', 'finance', 'Whether trading is enabled on the platform', NULL, 1, 13, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(41, 'wallets_enabled', '1', 'boolean', 'finance', 'Whether wallets are enabled on the platform', NULL, 1, 14, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(42, 'wallet_creation_enabled', '1', 'boolean', 'finance', 'Whether wallet creation is enabled on the platform', NULL, 1, 15, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(43, 'investments_enabled', '1', 'boolean', 'finance', 'Whether investments are enabled on the platform', NULL, 1, 16, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(44, 'create_withdrawal_pin', '1', 'boolean', 'finance', 'Require users to create a transaction PIN for withdrawals.', NULL, 1, 17, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(45, 'cron_investment_returns', '1', 'boolean', 'finance', 'Enable Cron for Investment Returns', 'If enabled, the system will automatically process investment returns based on the defined schedule.', 1, 18, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(46, 'cron_investment_returns_link', '* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1', 'text', 'finance', 'Cron Job Link for Investment Returns', 'Use this link to set up a cron job for processing investment returns.', 1, 19, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(47, 'require_kyc', '0', 'boolean', 'security', 'Require KYC Verification', 'Require users to complete KYC before withdrawing funds.', 1, 1, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(48, 'require_2fa_for_withdrawal', '1', 'boolean', 'security', 'Require 2FA for Withdrawals', NULL, 1, 2, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(49, 'require_pin_for_withdrawal', '1', 'boolean', 'security', 'Require Transaction PIN for Withdrawals', NULL, 1, 3, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(50, 'session_timeout_minutes', '30', 'number', 'security', 'Session Timeout (minutes)', NULL, 0, 4, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(51, 'max_login_attempts', '5', 'number', 'security', 'Max Login Attempts', NULL, 0, 5, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(52, 'two_factor_authentication', '1', 'boolean', 'security', 'Enable Two-Factor Authentication', NULL, 1, 6, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(53, 'require_email_verification', '0', 'boolean', 'security', 'Require Email Verification for New Users', NULL, 1, 7, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(54, 'registration_enabled', '1', 'boolean', 'security', 'Enable User Registration', NULL, 1, 8, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(55, 'mail_driver', 'smtp', 'select', 'mail', 'Mail Driver', NULL, 1, 1, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(56, 'mail_host', NULL, 'text', 'mail', 'Mail Host', NULL, 1, 2, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(57, 'mail_port', NULL, 'number', 'mail', 'Mail Port', NULL, 1, 3, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(58, 'mail_username', NULL, 'text', 'mail', 'Mail Username', NULL, 1, 4, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(59, 'mail_password', NULL, 'password', 'mail', 'Mail Password', NULL, 1, 5, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(60, 'mail_encryption', NULL, 'text', 'mail', 'Mail Encryption (tls/ssl)', NULL, 1, 6, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(61, 'mail_from_address', NULL, 'email', 'mail', '\"From\" Email Address for Outgoing Emails', NULL, 1, 7, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(62, 'mail_from_name', NULL, 'text', 'mail', '\"From\" Name for Outgoing Emails', NULL, 1, 8, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(63, 'mail_enabled', '0', 'boolean', 'mail', 'Enable Mail Notifications', NULL, 1, 0, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(64, 'referral_enabled', '1', 'boolean', 'referral', 'Enable Referral Program', NULL, 1, 1, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(65, 'referral_level_1_percentage', '5', 'number', 'referral', 'Direct Referral Commission (%)', NULL, 1, 2, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(66, 'referral_level_2_percentage', '2', 'number', 'referral', 'Indirect Referral Commission (%)', NULL, 1, 3, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(67, 'referral_bonus_signup', '0', 'number', 'referral', 'Signup Bonus Amount', NULL, 1, 4, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(68, 'copyright_text', '© 2026 NexVest. All rights reserved.', 'text', 'legal', 'Copyright Text', NULL, 1, 1, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(69, 'terms_url', '/terms-and-conditions', 'text', 'legal', 'Terms & Conditions URL', NULL, 1, 2, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(70, 'privacy_url', '/privacy-policy', 'text', 'legal', 'Privacy Policy URL', NULL, 1, 3, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(71, 'risk_disclosure_text', 'Investing involves risk, including potential loss of principal.', 'textarea', 'legal', 'Risk Disclosure Notice', NULL, 1, 4, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(72, 'primary_color', '#22823A', 'color', 'appearance', 'Primary Brand Color', NULL, 1, 1, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(73, 'secondary_color', '#3B82F6', 'color', 'appearance', 'Secondary Brand Color', NULL, 1, 2, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(74, 'default_theme', 'dark', 'select', 'appearance', 'Default Theme', 'Theme shown to first-time visitors.', 1, 3, '2026-08-05 19:33:31', '2026-08-13 16:04:20'),
(75, 'min_transfer_amount', '1', 'number', 'finance', 'Minimum Transfer Amount', NULL, 1, 20, '2026-08-07 18:38:50', '2026-08-13 16:04:20'),
(76, 'max_transfer_amount', '10000', 'number', 'finance', 'Maximum Transfer Amount', NULL, 1, 21, '2026-08-07 18:38:50', '2026-08-13 16:04:20'),
(77, 'trading_fee_percentage', '0.5', 'number', 'finance', 'Trading Fee (%)', NULL, 1, 11, '2026-08-07 20:19:27', '2026-08-13 16:04:20'),
(78, 'first_deposit_bonus_enabled', '1', 'boolean', 'finance', 'Enable First Deposit Bonus', NULL, 1, 12, '2026-08-10 03:54:32', '2026-08-13 16:04:20'),
(79, 'first_deposit_bonus_amount', '10', 'number', 'finance', 'First Deposit Bonus Amount', NULL, 1, 13, '2026-08-10 03:54:32', '2026-08-13 16:04:20'),
(80, 'first_deposit_bonus_type', 'fixed', 'select', 'finance', 'Bonus Type (fixed or percentage)', NULL, 1, 14, '2026-08-10 03:54:32', '2026-08-13 16:04:20');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `priority` enum('low','medium','high') NOT NULL,
  `status` enum('open','in_progress','pending','resolved','closed') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `user_id`, `subject`, `priority`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 'subject', 'medium', 'pending', '2026-08-08 15:30:36', '2026-08-11 19:05:07');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_ticket_id` bigint(20) UNSIGNED NOT NULL,
  `sender_type` enum('user','admin') NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_replies`
--

INSERT INTO `ticket_replies` (`id`, `support_ticket_id`, `sender_type`, `sender_id`, `message`, `attachment_path`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 'user', 5, 'this message ', 'images/tickets/ticket_1_6a77bc1cc9609.jpg', 1, '2026-08-08 15:30:36', '2026-08-10 08:44:02'),
(2, 1, 'user', 5, 'this is message ', NULL, 1, '2026-08-08 15:32:51', '2026-08-10 08:44:02'),
(3, 1, 'admin', 1, 'this is just the admin test', NULL, 1, '2026-08-11 19:05:07', '2026-08-11 19:05:07'),
(4, 1, 'admin', 1, 'you need to test the image', 'images/tickets/reply_admin_6a7be40569f4e.jpeg', 1, '2026-08-11 19:09:57', '2026-08-11 19:09:57');

-- --------------------------------------------------------

--
-- Table structure for table `trades`
--

CREATE TABLE `trades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `trading_pair_id` bigint(20) UNSIGNED NOT NULL,
  `side` enum('buy','sell') NOT NULL,
  `order_type` enum('market','limit','stop_loss','take_profit') NOT NULL,
  `amount` decimal(24,8) NOT NULL,
  `price` decimal(24,8) NOT NULL,
  `total` decimal(24,8) NOT NULL,
  `status` enum('open','filled','hit_target','hit_stop','expired','cancelled') NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trades`
--

INSERT INTO `trades` (`id`, `user_id`, `trading_pair_id`, `side`, `order_type`, `amount`, `price`, `total`, `status`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'buy', 'market', 0.00039314, 64913.00000000, 25.65000000, 'filled', NULL, '2026-08-09 03:39:48', '2026-08-09 03:39:48'),
(2, 5, 1, 'sell', 'market', 0.00040000, 64913.00000000, 26.10000000, 'filled', NULL, '2026-08-09 03:40:57', '2026-08-09 03:40:57');

-- --------------------------------------------------------

--
-- Table structure for table `trading_pairs`
--

CREATE TABLE `trading_pairs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `base_currency_id` bigint(20) UNSIGNED NOT NULL,
  `quote_currency_id` bigint(20) UNSIGNED NOT NULL,
  `current_price` decimal(24,8) NOT NULL,
  `change_24h_percent` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trading_pairs`
--

INSERT INTO `trading_pairs` (`id`, `symbol`, `base_currency_id`, `quote_currency_id`, `current_price`, `change_24h_percent`, `created_at`, `updated_at`) VALUES
(1, 'BTCUSDT', 2, 4, 64913.00000000, 0.10, '2026-08-07 20:23:52', '2026-08-08 14:57:03'),
(2, 'ETHUSDT', 3, 4, 1917.72000000, 0.23, '2026-08-07 20:23:52', '2026-08-08 14:57:03'),
(3, 'SOLUSDT', 7, 4, 76.01000000, 3.30, '2026-08-07 20:23:52', '2026-08-08 14:57:03'),
(4, 'DOGEUSDT', 8, 4, 0.07053800, 1.31, '2026-08-07 20:23:52', '2026-08-08 14:57:03'),
(5, 'XRPUSDT', 9, 4, 1.03900000, 1.87, '2026-08-07 20:23:52', '2026-08-08 14:57:03');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `wallet_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(350) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `direction` enum('debit','credit') NOT NULL,
  `amount` decimal(28,18) NOT NULL,
  `fee` decimal(28,18) NOT NULL DEFAULT 0.000000000000000000,
  `currency` varchar(10) NOT NULL,
  `status` enum('pending','completed','failed','reversed') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `failed_reason` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `wallet_id`, `type`, `reference`, `direction`, `amount`, `fee`, `currency`, `status`, `description`, `failed_reason`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 'deposit', 'DEP-FXWJVFAKGX', 'credit', 500.000000000000000000, 0.000000000000000000, 'USD', 'pending', 'Deposit via Crypto', NULL, '{\"gateway_id\":3,\"gateway_name\":\"Crypto\",\"total\":500}', '2026-08-07 16:05:19', '2026-08-07 16:05:19'),
(2, 5, NULL, 'withdrawal', 'WD-EVEI7HXVEE', 'debit', 50.000000000000000000, 2.400000000000000000, 'BTC', 'pending', 'Withdrawal via Bitcoin', NULL, '{\"gateway_id\":4,\"gateway_name\":\"Bitcoin\",\"balance_source\":\"balance\",\"total\":52.4}', '2026-08-07 17:30:03', '2026-08-07 17:30:03'),
(3, 5, NULL, 'transfer_out', 'TRF-JEGLPFOW6Q-S', 'debit', 23.000000000000000000, 0.000000000000000000, 'USD', 'completed', 'Transfer to sunday-daniel1623: this is a test', NULL, '{\"transfer_reference\":\"TRF-JEGLPFOW6Q\",\"recipient_id\":1,\"recipient_username\":\"sunday-daniel1623\"}', '2026-08-07 18:58:50', '2026-08-07 18:58:50'),
(4, 5, NULL, 'transfer_in', 'TRF-JEGLPFOW6Q-R', 'credit', 23.000000000000000000, 0.000000000000000000, 'USD', 'completed', 'Transfer from emmanuel-sunday9812: this is a test', NULL, '{\"transfer_reference\":\"TRF-JEGLPFOW6Q\",\"sender_id\":5,\"sender_username\":\"emmanuel-sunday9812\"}', '2026-08-07 18:58:50', '2026-08-07 18:58:50'),
(5, 5, NULL, 'investment', 'trx-yCf5u1i1FM4T', 'debit', 50.000000000000000000, 0.000000000000000000, 'USD', 'completed', 'Invested in Starter Plan', NULL, '{\"investment_id\":1,\"plan_name\":\"Starter Plan\",\"expected_return\":55.25,\"ends_at\":\"2026-08-15 15:06:32\"}', '2026-08-08 07:06:32', '2026-08-08 07:06:32'),
(6, 5, NULL, 'profit', 'trx-EpiMR9iyO71Z', 'credit', 0.750000000000000000, 0.000000000000000000, 'USD', 'completed', 'Profit payout — Starter Plan', NULL, '{\"investment_id\":1,\"plan_name\":\"Starter Plan\"}', '2026-08-08 15:02:47', '2026-08-08 15:02:47'),
(8, 5, 2, 'exchange', 'SWP-KVKAV1300IOL', 'debit', 1.150000000000000000, 0.000000000000000000, 'USD', 'completed', 'Swap $1.15 USD → 0.00001771 BTC @ $64930', NULL, '{\"mode\":\"deposit\",\"to_wallet_id\":2,\"to_currency\":\"BTC\",\"rate\":64930,\"crypto_amount\":1.7711381487756044e-5}', '2026-08-09 03:27:07', '2026-08-09 03:27:07'),
(9, 5, 2, 'exchange', 'SWP-8REFV1MDSVJ4', 'debit', 0.860000000000000000, 0.000000000000000000, 'USD', 'completed', 'Swap $0.86 USD → 0.00001325 BTC @ $64930', NULL, '{\"mode\":\"deposit\",\"to_wallet_id\":2,\"to_currency\":\"BTC\",\"rate\":64930,\"crypto_amount\":1.324503311258278e-5}', '2026-08-09 03:29:54', '2026-08-09 03:29:54'),
(10, 5, 2, 'exchange', 'SWP-CRXDE5RN9YAL', 'credit', 100.000000000000000000, 0.000000000000000000, 'USD', 'completed', 'Swap 0.00154014 BTC → $100 USD @ $64929', NULL, '{\"mode\":\"withdraw\",\"from_wallet_id\":2,\"from_currency\":\"BTC\",\"rate\":64929,\"crypto_amount\":0.0015401438494355372}', '2026-08-09 03:32:17', '2026-08-09 03:32:17'),
(11, 5, NULL, 'trade', 'TRD-YRAA8GZCVA-1', 'debit', 25.650000000000000000, 0.130000000000000000, 'USDT', 'completed', 'Bought 0.00039314 BTC @ $64,913.00/BTC', NULL, '{\"trade_id\":1,\"pair\":\"BTCUSDT\",\"side\":\"buy\"}', '2026-08-09 03:39:48', '2026-08-09 03:39:48'),
(12, 5, 2, 'trade', 'TRD-YRAA8GZCVA-2', 'credit', 0.000393140000000000, 0.000000000000000000, 'BTC', 'completed', 'Received 0.00039314 BTC from buying BTCUSDT', NULL, '{\"trade_id\":1,\"pair\":\"BTCUSDT\",\"side\":\"buy\"}', '2026-08-09 03:39:48', '2026-08-09 03:39:48'),
(13, 5, 2, 'trade', 'TRD-6PIO3KAPFK-1', 'debit', 0.000400000000000000, 0.000000000000000000, 'BTC', 'completed', 'Sold 0.0004 BTC @ $64,913.00/BTC', NULL, '{\"trade_id\":2,\"pair\":\"BTCUSDT\",\"side\":\"sell\"}', '2026-08-09 03:40:57', '2026-08-09 03:40:57'),
(14, 5, NULL, 'trade', 'TRD-6PIO3KAPFK-2', 'credit', 25.835200000000000000, 0.130000000000000000, 'USDT', 'completed', 'Received $25.84 from selling 0.0004 BTC', NULL, '{\"trade_id\":2,\"pair\":\"BTCUSDT\",\"side\":\"sell\"}', '2026-08-09 03:40:57', '2026-08-09 03:40:57'),
(16, 5, NULL, 'other', 'trx-Xm10UNnMyNN1', 'debit', 77.090000000000000000, 0.000000000000000000, 'USD', 'completed', 'Description', NULL, '{\"adjusted_by_admin\":true,\"column\":\"balance\"}', '2026-08-10 15:11:07', '2026-08-10 15:11:07'),
(17, 5, NULL, 'other', 'trx-ZqeABUR5iC3w', 'debit', 0.000000000000000000, 0.000000000000000000, 'USD', 'completed', 'this is a test', NULL, '{\"adjusted_by_admin\":true,\"column\":\"balance\"}', '2026-08-10 15:38:29', '2026-08-10 15:38:29'),
(18, 5, NULL, 'other', 'trx-C0GAevzLkNnb', 'credit', 200.000000000000000000, 0.000000000000000000, 'USD', 'completed', 'this is a test', NULL, '{\"adjusted_by_admin\":true,\"column\":\"balance\"}', '2026-08-10 15:39:06', '2026-08-10 15:39:06'),
(19, 5, NULL, 'investment', 'trx-nVn4PkZec9Qf', 'debit', 90.000000000000000000, 0.000000000000000000, 'USD', 'completed', 'Invested in Starter Plan', NULL, '{\"investment_id\":2,\"plan_name\":\"Starter Plan\",\"expected_return\":99.45,\"ends_at\":\"2026-08-18 23:48:57\"}', '2026-08-11 15:48:57', '2026-08-11 15:48:57'),
(20, 5, NULL, 'investment', 'trx-KWks8pIJSIza', 'debit', 100.000000000000000000, 0.000000000000000000, 'USD', 'completed', 'Invested in Quick Flip Plan', NULL, '{\"investment_id\":3,\"plan_name\":\"Quick Flip Plan\",\"expected_return\":112,\"ends_at\":\"2026-08-14 23:50:18\"}', '2026-08-11 15:50:18', '2026-08-11 15:50:18'),
(21, 5, 2, 'exchange', 'SWP-MGKCQDSLZCVX', 'credit', 0.090000000000000000, 0.000000000000000000, 'USD', 'completed', 'Swap 0.00000141 BTC → $0.09 USD @ $63914', NULL, '{\"mode\":\"withdraw\",\"from_wallet_id\":2,\"from_currency\":\"BTC\",\"rate\":63914,\"crypto_amount\":1.4081421910692494e-6}', '2026-08-12 05:17:22', '2026-08-12 05:17:22');

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(24,8) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `reference` varchar(255) NOT NULL,
  `status` enum('completed','pending','failed') NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfers`
--

INSERT INTO `transfers` (`id`, `sender_id`, `recipient_id`, `amount`, `description`, `reference`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 23.00000000, 'this is a test', 'TRF-JEGLPFOW6Q', 'completed', '2026-08-07 18:58:50', '2026-08-07 18:58:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'images/user/user.png',
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `profit_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `kyc_status` enum('not_submitted','pending','approved','rejected') NOT NULL DEFAULT 'not_submitted',
  `status` enum('active','suspended','banned') NOT NULL DEFAULT 'active',
  `deposit_status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `deposit_message` text NOT NULL DEFAULT 'Deposit is unavailable at the moment',
  `transfer_status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `transfer_message` text NOT NULL DEFAULT 'Transfer is unavailable at the moment',
  `withdrawal_status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `withdrawal_message` text NOT NULL DEFAULT 'Withdrawal is unavailable at the moment',
  `investment_status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `investment_message` text NOT NULL DEFAULT 'Investment is unavailable at the moment',
  `trading_status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `trading_message` text NOT NULL DEFAULT 'Trading is unavailable at the moment',
  `withdrawal_fee_status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `withdrawal_fee` decimal(10,2) NOT NULL DEFAULT 45.00,
  `withdrawal_fee_type` enum('percentage','amount') NOT NULL DEFAULT 'percentage',
  `daily_transfer_limit` decimal(15,2) NOT NULL DEFAULT 10000.00,
  `daily_withdrawal_limit` decimal(15,2) NOT NULL DEFAULT 10000.00,
  `weekly_transfer_limit` decimal(15,2) NOT NULL DEFAULT 50000.00,
  `weekly_withdrawal_limit` decimal(15,2) NOT NULL DEFAULT 50000.00,
  `monthly_transfer_limit` decimal(15,2) NOT NULL DEFAULT 200000.00,
  `monthly_withdrawal_limit` decimal(15,2) NOT NULL DEFAULT 200000.00,
  `default_theme` enum('light','dark') NOT NULL DEFAULT 'dark',
  `referral_code` varchar(255) NOT NULL,
  `referred_by` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_pin` varchar(255) DEFAULT NULL,
  `pin_update_at` date DEFAULT NULL,
  `biometric_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `phone`, `city`, `state`, `country`, `address`, `avatar`, `balance`, `profit_balance`, `kyc_status`, `status`, `deposit_status`, `deposit_message`, `transfer_status`, `transfer_message`, `withdrawal_status`, `withdrawal_message`, `investment_status`, `investment_message`, `trading_status`, `trading_message`, `withdrawal_fee_status`, `withdrawal_fee`, `withdrawal_fee_type`, `daily_transfer_limit`, `daily_withdrawal_limit`, `weekly_transfer_limit`, `weekly_withdrawal_limit`, `monthly_transfer_limit`, `monthly_withdrawal_limit`, `default_theme`, `referral_code`, `referred_by`, `transaction_pin`, `pin_update_at`, `biometric_enabled`, `last_login_at`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'sunday daniel', 'sunday@gmail.com', 'sunday-daniel1623', '+120988888', NULL, NULL, 'Nigeria', NULL, 'images/user/user.png', 23.00, 0.00, 'not_submitted', 'active', 'enabled', 'Deposit is unavailable at the moment', 'enabled', 'Transfer is unavailable at the moment', 'enabled', 'Withdrawal is unavailable at the moment', 'enabled', 'Investment is unavailable at the moment', 'enabled', 'Trading is unavailable at the moment', 'enabled', 45.00, 'percentage', 10000.00, 10000.00, 50000.00, 50000.00, 200000.00, 200000.00, 'dark', 'BZOMFHB5HL', NULL, NULL, NULL, 0, '2026-08-09 14:08:53', NULL, '$2y$12$lkFhJUXgspsyH.xvAS4yO.g7wL2L9BXAeH4cVfATO4nzklScUbYqO', 'OIXTANMBGFISYKH546HFW5N3VSVEGLWK', NULL, NULL, NULL, '2026-08-06 16:52:39', '2026-08-12 06:39:05'),
(5, 'emmanuel sunday', 'sundaydaniek@gmail.com', 'emmanuel-sunday9812', '0908998766', NULL, NULL, 'Nigeria', NULL, NULL, 10.09, 100.75, 'approved', 'active', 'enabled', 'Deposit is unavailable at the moment', 'enabled', 'Transfer is unavailable at the moment', 'enabled', 'Withdrawal is unavailable at the moment', 'enabled', 'Investment is unavailable at the moment', 'enabled', 'Trading is unavailable at the moment', 'enabled', 45.00, 'percentage', 10000.00, 10000.00, 50000.00, 50000.00, 200000.00, 200000.00, 'dark', 'YNEIK6MHXH', NULL, '$2y$12$0n1RA6sLnnKVgP1cPJ2caOGXLLJH2rBUiPrK2A3WwHcGr1jzrgn82', '2026-08-09', 0, '2026-08-13 14:58:04', NULL, '$2y$12$66LECJnC.QrieFECGNyU5.s9pOseGPmCec5lrZuFQiQqwr2k3/TBK', NULL, NULL, NULL, '5x2AtzQeV71OsBWasL5Z2cs0szwFcSB1gyFTSF5YKLCnCv5xKV0lQfmVxtUn', '2026-08-06 17:24:58', '2026-08-13 16:21:02');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `available` decimal(24,8) NOT NULL DEFAULT 0.00000000,
  `locked` decimal(24,8) NOT NULL DEFAULT 0.00000000,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `currency_id`, `available`, `locked`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 5, 4, 355.70683798, 0.00000000, 0, '2026-08-07 20:28:53', '2026-08-09 04:02:29'),
(2, 5, 2, 30.39848255, 0.00000000, 0, '2026-08-07 20:29:02', '2026-08-12 05:17:22'),
(3, 5, 7, 10.20000000, 0.00000000, 0, '2026-08-07 20:48:01', '2026-08-07 20:48:01'),
(4, 5, 8, 10.20000000, 0.00000000, 0, '2026-08-07 20:48:05', '2026-08-07 20:48:05'),
(5, 1, 4, 0.00000000, 0.00000000, 0, '2026-08-07 21:05:18', '2026-08-07 21:05:18'),
(6, 5, 10, 10.20000000, 0.00000000, 0, '2026-08-08 06:22:43', '2026-08-08 06:22:43'),
(7, 1, 8, 0.00000000, 0.00000000, 0, '2026-08-08 07:49:15', '2026-08-08 07:49:15'),
(8, 5, 3, 30.00000000, 0.00000000, 0, '2026-08-08 22:03:40', '2026-08-08 22:03:40'),
(9, 5, 5, 4440.00000000, 0.00000000, 0, '2026-08-08 22:04:41', '2026-08-08 22:04:41'),
(10, 1, 2, 0.00000000, 0.00000000, 0, '2026-08-09 03:58:06', '2026-08-09 03:58:06');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) NOT NULL,
  `method` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','paid') NOT NULL DEFAULT 'pending',
  `rejection_reason` varchar(255) DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `user_id`, `amount`, `fee`, `currency`, `method`, `transaction_id`, `status`, `rejection_reason`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 5, 50.00, 2.40, 'BTC', 'Bitcoin', 'WD-EVEI7HXVEE', 'pending', NULL, '{\"wallet_address\":\"mywalletaddress\",\"balance_source\":\"balance\"}', '2026-08-07 17:30:03', '2026-08-07 17:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_gateways`
--

CREATE TABLE `withdraw_gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `min_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `max_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fixed_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `percent_fee` decimal(5,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(255) NOT NULL DEFAULT 'USD',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdraw_gateways`
--

INSERT INTO `withdraw_gateways` (`id`, `name`, `code`, `logo`, `details`, `min_amount`, `max_amount`, `fixed_fee`, `percent_fee`, `currency`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bank Transfer', 'bank_transfer', NULL, '[{\"name\":\"bank_name\",\"label\":\"Bank Name\",\"type\":\"text\",\"required\":true},{\"name\":\"account_name\",\"label\":\"Account Holder Name\",\"type\":\"text\",\"required\":true},{\"name\":\"account_number\",\"label\":\"Account Number\",\"type\":\"text\",\"required\":true},{\"name\":\"routing_number\",\"label\":\"Routing \\/ SWIFT Code\",\"type\":\"text\",\"required\":false}]', 20.00, 10000.00, 1.50, 1.00, 'USD', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(2, 'USDT (TRC20)', 'usdt_trc20', NULL, '[{\"name\":\"wallet_address\",\"label\":\"USDT Wallet Address (TRC20)\",\"type\":\"text\",\"required\":true}]', 10.00, 50000.00, 1.00, 0.50, 'USDT', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(3, 'USDT (ERC20)', 'usdt_erc20', NULL, '[{\"name\":\"wallet_address\",\"label\":\"USDT Wallet Address (ERC20)\",\"type\":\"text\",\"required\":true}]', 20.00, 50000.00, 3.00, 0.50, 'USDT', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(4, 'Bitcoin', 'bitcoin', 'images/gateway/bitcoin.png', '[{\"name\":\"wallet_address\",\"label\":\"BTC Wallet Address\",\"type\":\"text\",\"required\":true}]', 15.00, 100000.00, 2.00, 0.80, 'BTC', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31'),
(5, 'PayPal', 'paypal', 'images/gateway/paypal_1786502350_6a7bdcce9d3f1.png', '[{\"name\":\"paypal_email\",\"label\":\"PayPal Email Address\",\"type\":\"text\",\"required\":true}]', 10.00, 5000.00, 0.50, 2.50, 'USD', 1, '2026-08-05 19:33:31', '2026-08-11 18:39:10'),
(6, 'Skrill', 'skrill', NULL, '[{\"name\":\"skrill_email\",\"label\":\"Skrill Email Address\",\"type\":\"text\",\"required\":true}]', 10.00, 5000.00, 0.50, 2.00, 'USD', 1, '2026-08-05 19:33:31', '2026-08-05 19:33:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `cron_logs`
--
ALTER TABLE `cron_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cron_logs_name_unique` (`name`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deposits_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investments_user_id_foreign` (`user_id`),
  ADD KEY `investments_investment_plan_id_foreign` (`investment_plan_id`);

--
-- Indexes for table `investment_plans`
--
ALTER TABLE `investment_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `investment_plans_slug_unique` (`slug`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kycs`
--
ALTER TABLE `kycs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kyc_documents`
--
ALTER TABLE `kyc_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kyc_documents_user_id_foreign` (`user_id`),
  ADD KEY `kyc_documents_kyc_id_foreign` (`kyc_id`);

--
-- Indexes for table `login_activities`
--
ALTER TABLE `login_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_activities_user_id_foreign` (`user_id`);

--
-- Indexes for table `mail_templates`
--
ALTER TABLE `mail_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `money_requests`
--
ALTER TABLE `money_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `money_requests_requester_id_foreign` (`requester_id`),
  ADD KEY `money_requests_recipient_id_foreign` (`recipient_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `policies_slug_unique` (`slug`);

--
-- Indexes for table `profit_logs`
--
ALTER TABLE `profit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profit_logs_user_id_foreign` (`user_id`),
  ADD KEY `profit_logs_investment_id_foreign` (`investment_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referrals_referrer_id_foreign` (`referrer_id`),
  ADD KEY `referrals_referred_id_foreign` (`referred_id`);

--
-- Indexes for table `referral_commissions`
--
ALTER TABLE `referral_commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referral_commissions_referral_id_foreign` (`referral_id`),
  ADD KEY `referral_commissions_source_transaction_id_foreign` (`source_transaction_id`);

--
-- Indexes for table `security_alerts`
--
ALTER TABLE `security_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `security_alerts_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_user_id_foreign` (`user_id`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_replies_support_ticket_id_foreign` (`support_ticket_id`);

--
-- Indexes for table `trades`
--
ALTER TABLE `trades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trades_user_id_foreign` (`user_id`),
  ADD KEY `trades_trading_pair_id_foreign` (`trading_pair_id`);

--
-- Indexes for table `trading_pairs`
--
ALTER TABLE `trading_pairs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trading_pairs_symbol_unique` (`symbol`),
  ADD KEY `trading_pairs_base_currency_id_foreign` (`base_currency_id`),
  ADD KEY `trading_pairs_quote_currency_id_foreign` (`quote_currency_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_reference_unique` (`reference`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_wallet_id_foreign` (`wallet_id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transfers_reference_unique` (`reference`),
  ADD KEY `transfers_sender_id_foreign` (`sender_id`),
  ADD KEY `transfers_recipient_id_foreign` (`recipient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  ADD KEY `users_referred_by_foreign` (`referred_by`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallets_user_id_foreign` (`user_id`),
  ADD KEY `wallets_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `withdrawals_user_id_foreign` (`user_id`);

--
-- Indexes for table `withdraw_gateways`
--
ALTER TABLE `withdraw_gateways`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cron_logs`
--
ALTER TABLE `cron_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `investment_plans`
--
ALTER TABLE `investment_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kycs`
--
ALTER TABLE `kycs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kyc_documents`
--
ALTER TABLE `kyc_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `login_activities`
--
ALTER TABLE `login_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mail_templates`
--
ALTER TABLE `mail_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `money_requests`
--
ALTER TABLE `money_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `profit_logs`
--
ALTER TABLE `profit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_commissions`
--
ALTER TABLE `referral_commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `security_alerts`
--
ALTER TABLE `security_alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trades`
--
ALTER TABLE `trades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trading_pairs`
--
ALTER TABLE `trading_pairs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `withdraw_gateways`
--
ALTER TABLE `withdraw_gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deposits`
--
ALTER TABLE `deposits`
  ADD CONSTRAINT `deposits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investments`
--
ALTER TABLE `investments`
  ADD CONSTRAINT `investments_investment_plan_id_foreign` FOREIGN KEY (`investment_plan_id`) REFERENCES `investment_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `investments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kyc_documents`
--
ALTER TABLE `kyc_documents`
  ADD CONSTRAINT `kyc_documents_kyc_id_foreign` FOREIGN KEY (`kyc_id`) REFERENCES `kycs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kyc_documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_activities`
--
ALTER TABLE `login_activities`
  ADD CONSTRAINT `login_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `money_requests`
--
ALTER TABLE `money_requests`
  ADD CONSTRAINT `money_requests_recipient_id_foreign` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `money_requests_requester_id_foreign` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profit_logs`
--
ALTER TABLE `profit_logs`
  ADD CONSTRAINT `profit_logs_investment_id_foreign` FOREIGN KEY (`investment_id`) REFERENCES `investments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `profit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_referred_id_foreign` FOREIGN KEY (`referred_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referrals_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referral_commissions`
--
ALTER TABLE `referral_commissions`
  ADD CONSTRAINT `referral_commissions_referral_id_foreign` FOREIGN KEY (`referral_id`) REFERENCES `referrals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referral_commissions_source_transaction_id_foreign` FOREIGN KEY (`source_transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `security_alerts`
--
ALTER TABLE `security_alerts`
  ADD CONSTRAINT `security_alerts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_support_ticket_id_foreign` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trades`
--
ALTER TABLE `trades`
  ADD CONSTRAINT `trades_trading_pair_id_foreign` FOREIGN KEY (`trading_pair_id`) REFERENCES `trading_pairs` (`id`),
  ADD CONSTRAINT `trades_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trading_pairs`
--
ALTER TABLE `trading_pairs`
  ADD CONSTRAINT `trading_pairs_base_currency_id_foreign` FOREIGN KEY (`base_currency_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `trading_pairs_quote_currency_id_foreign` FOREIGN KEY (`quote_currency_id`) REFERENCES `currencies` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_recipient_id_foreign` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfers_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_referred_by_foreign` FOREIGN KEY (`referred_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD CONSTRAINT `withdrawals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
