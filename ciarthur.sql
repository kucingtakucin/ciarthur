-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Oct 21, 2021 at 02:40 PM
-- Server version: 10.6.4-MariaDB-1:10.6.4+maria~focal
-- PHP Version: 7.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ciarthur`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(40) NOT NULL DEFAULT '',
  `all_access` tinyint(1) NOT NULL DEFAULT 0,
  `controller` varchar(50) NOT NULL DEFAULT '',
  `date_created` datetime DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('3ed3c221d701afb0b5243f92b042e553132cb52a', '172.31.0.1', 1634827050, 0x5f5f63695f6c6173745f726567656e65726174657c693a313633343832363930343b6964656e746974797c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31333a2261646d696e6973747261746f72223b656d61696c7c733a31353a2261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2231223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231363334383039383334223b6c6173745f636865636b7c693a313633343831383931353b696f6e5f617574685f73657373696f6e5f686173687c733a38303a226131616539636435393865396661353765326631316630323536356633373630613064316366306562623037323465386464353037613164613965316137616233353733353363303633316139333461223b);

-- --------------------------------------------------------

--
-- Table structure for table `fakultas`
--

CREATE TABLE `fakultas` (
  `id` bigint(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `is_active` enum('0','1') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `fakultas`
--

INSERT INTO `fakultas` (`id`, `nama`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 'SENI RUPA DAN DESAIN', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'ILMU SOSIAL DAN POLITIK', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'HUKUM', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'EKONOMI DAN BISNIS', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'KEDOKTERAN', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'PERTANIAN', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'TEKNIK', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'KEGURUAN DAN ILMU PENDIDIKAN', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'MATEMATIKA DAN ILMU PENGETAHUAN ALAM', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'ILMU BUDAYA', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'KEOLAHRAGAAN', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'VOKASI', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'Coba2', '0', '2021-10-12 09:29:00', 1, '2021-10-12 09:53:06', 1, '2021-10-12 09:55:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kecamatan`
--

CREATE TABLE `kecamatan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `longitude` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `kecamatan`
--

INSERT INTO `kecamatan` (`id`, `nama`, `latitude`, `longitude`) VALUES
(1, 'Kec. Jatipuro', '-7.74449', '111.0138203'),
(2, 'Kec. Jatiyoso', '-7.704725', '111.1035213'),
(6, 'Kec. Tawangmangu', '-7.661433', '111.1271453'),
(5, 'Kec. Matesih', '-7.641355', '111.0377783'),
(4, 'Kec. Jumantono', '-7.669329', '110.9841663'),
(7, 'Kec. Ngargoyoso', '-7.602591', '111.1036263'),
(3, 'Kec. Jumapolo', '-7.71191', '110.9601813'),
(9, 'Kec. Karanganyar', '-7.5988', '110.9485'),
(8, 'Kec. Karangpandan', '-7.6045', '111.0703'),
(10, 'Kec. Tasikmadu', '-7.57096', '110.9363933'),
(11, 'Kec. Jaten', '-7.559027', '110.8827473'),
(14, 'Kec. Kebakkramat', '-7.511256', '110.9125403'),
(12, 'Kec. Colomadu', '-7.53242', '110.74892'),
(13, 'Kec. Gondangrejo', '-7.495469', '110.8421953'),
(15, 'Kec. Mojogedang', '-7.546056', '110.9842383'),
(16, 'Kec. Kerjo', '-7.536727', '111.0618333'),
(17, 'Kec. Jenawi', '-7.539923', '111.1273113');

-- --------------------------------------------------------

--
-- Table structure for table `limits`
--

CREATE TABLE `limits` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `count` int(10) NOT NULL,
  `hour_started` int(11) NOT NULL,
  `api_key` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text DEFAULT NULL,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `uri`, `method`, `params`, `api_key`, `ip_address`, `time`, `rtime`, `authorized`, `response_code`) VALUES
(1, 'api/mahasiswa', 'get', 'a:16:{s:4:\"Host\";s:13:\"appt.demoo.id\";s:9:\"X-Real-IP\";s:12:\"36.65.98.157\";s:15:\"X-Forwarded-For\";s:12:\"36.65.98.157\";s:17:\"X-Forwarded-Proto\";s:5:\"https\";s:10:\"Connection\";s:5:\"close\";s:10:\"User-Agent\";s:78:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:92.0) Gecko/20100101 Firefox/92.0\";s:6:\"Accept\";s:74:\"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\";s:15:\"Accept-Language\";s:14:\"en-US,en;q=0.5\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:25:\"Upgrade-Insecure-Requests\";s:1:\"1\";s:14:\"Sec-Fetch-Dest\";s:8:\"document\";s:14:\"Sec-Fetch-Mode\";s:8:\"navigate\";s:14:\"Sec-Fetch-Site\";s:4:\"none\";s:14:\"Sec-Fetch-User\";s:2:\"?1\";s:13:\"Authorization\";s:248:\"Digest username=\"administrator\", realm=\"REST API\", nonce=\"6153bbb842a22\", uri=\"/omahan/ciarthur/api/mahasiswa\", response=\"0d23bb6833c79cb084ff579b87eef910\", opaque=\"aba3d4b49c454e1974970e7b5514b001\", qop=auth, nc=00000001, cnonce=\"082c875dcb2ca740\"\";s:6:\"Cookie\";s:53:\"ciarthur_csrf_cookie=cbb4dec0116a92091e6758224fc4f1bc\";}', '', '172.31.0.1', 1632877505, 0.0336301, '0', 403),
(2, 'api/mahasiswa', 'get', 'a:11:{s:4:\"Host\";s:13:\"appt.demoo.id\";s:9:\"X-Real-IP\";s:12:\"36.65.98.157\";s:15:\"X-Forwarded-For\";s:12:\"36.65.98.157\";s:17:\"X-Forwarded-Proto\";s:5:\"https\";s:10:\"Connection\";s:5:\"close\";s:6:\"Accept\";s:16:\"application/json\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.28.3\";s:13:\"Postman-Token\";s:36:\"d6853c5a-9888-4ad6-8780-2f105e7dc5f7\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:6:\"Cookie\";s:226:\"ciarthur_csrf_cookie=b4c2fb482b57d813267f2dae3b625af9; ciarthur_session=8fd6801f5b6add39fdbf8408b06c5f2815a11e2b; ciarthur_csrf_cookie=b4c2fb482b57d813267f2dae3b625af9; ciarthur_session=8fd6801f5b6add39fdbf8408b06c5f2815a11e2b\";s:13:\"Authorization\";s:257:\"Digest username=\"administrator\", realm=\"REST API\", nonce=\"6153bce879f77\", uri=\"/omahan/ciarthur/api/mahasiswa\", algorithm=\"MD5\", qop=auth, nc=00000001, cnonce=\"FMyHOxow\", response=\"580d3e3332e107035abe9d2e89f1fd91\", opaque=\"aba3d4b49c454e1974970e7b5514b001\"\";}', '', '172.31.0.1', 1632877800, 0.034703, '0', 403),
(3, 'api/auth/login', 'get', 'a:11:{s:4:\"Host\";s:13:\"appt.demoo.id\";s:9:\"X-Real-IP\";s:12:\"36.65.98.157\";s:15:\"X-Forwarded-For\";s:12:\"36.65.98.157\";s:17:\"X-Forwarded-Proto\";s:5:\"https\";s:10:\"Connection\";s:5:\"close\";s:6:\"Accept\";s:16:\"application/json\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.28.3\";s:13:\"Postman-Token\";s:36:\"cc7c1433-1778-474d-a0a7-b43193d5daa7\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:6:\"Cookie\";s:226:\"ciarthur_csrf_cookie=b4c2fb482b57d813267f2dae3b625af9; ciarthur_session=0d7955c8fe8ecc69e7ed42d5547bf0b6b50db217; ciarthur_csrf_cookie=b4c2fb482b57d813267f2dae3b625af9; ciarthur_session=0d7955c8fe8ecc69e7ed42d5547bf0b6b50db217\";s:13:\"Authorization\";s:258:\"Digest username=\"administrator\", realm=\"REST API\", nonce=\"6153c1176ef74\", uri=\"/omahan/ciarthur/api/auth/login\", algorithm=\"MD5\", qop=auth, nc=00000001, cnonce=\"2mCdTKEN\", response=\"358e9515a34a23295d972de20ef4ea39\", opaque=\"aba3d4b49c454e1974970e7b5514b001\"\";}', '', '172.31.0.1', 1632878871, 0.0171611, '0', 403),
(4, 'api/auth/login', 'get', 'a:11:{s:4:\"Host\";s:13:\"appt.demoo.id\";s:9:\"X-Real-IP\";s:12:\"36.65.98.157\";s:15:\"X-Forwarded-For\";s:12:\"36.65.98.157\";s:17:\"X-Forwarded-Proto\";s:5:\"https\";s:10:\"Connection\";s:5:\"close\";s:6:\"Accept\";s:16:\"application/json\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.28.3\";s:13:\"Postman-Token\";s:36:\"5d031811-60c0-46ca-a98c-1e5631c5de29\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:6:\"Cookie\";s:226:\"ciarthur_csrf_cookie=b4c2fb482b57d813267f2dae3b625af9; ciarthur_session=0d7955c8fe8ecc69e7ed42d5547bf0b6b50db217; ciarthur_csrf_cookie=b4c2fb482b57d813267f2dae3b625af9; ciarthur_session=0d7955c8fe8ecc69e7ed42d5547bf0b6b50db217\";s:13:\"Authorization\";s:258:\"Digest username=\"administrator\", realm=\"REST API\", nonce=\"6153c127ad7b8\", uri=\"/omahan/ciarthur/api/auth/login\", algorithm=\"MD5\", qop=auth, nc=00000001, cnonce=\"lHaaUuSp\", response=\"8546a5e191a5e4de6062ce1bff67718d\", opaque=\"aba3d4b49c454e1974970e7b5514b001\"\";}', '', '172.31.0.1', 1632878887, 0.013659, '0', 403),
(5, 'api/auth/login', 'get', 'a:10:{s:4:\"Host\";s:13:\"appt.demoo.id\";s:9:\"X-Real-IP\";s:12:\"36.65.98.157\";s:15:\"X-Forwarded-For\";s:12:\"36.65.98.157\";s:17:\"X-Forwarded-Proto\";s:5:\"https\";s:10:\"Connection\";s:5:\"close\";s:6:\"Accept\";s:16:\"application/json\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.28.3\";s:13:\"Postman-Token\";s:36:\"91886b76-8450-498a-b28d-d9ab97914de4\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:6:\"Cookie\";s:112:\"ciarthur_csrf_cookie=b4c2fb482b57d813267f2dae3b625af9; ciarthur_session=0d7955c8fe8ecc69e7ed42d5547bf0b6b50db217\";}', '', '172.31.0.1', 1632879135, 0.016762, '0', 403),
(6, 'api/mahasiswa/index', 'get', 'a:10:{s:4:\"Host\";s:13:\"appt.demoo.id\";s:9:\"X-Real-IP\";s:12:\"36.65.98.157\";s:15:\"X-Forwarded-For\";s:12:\"36.65.98.157\";s:17:\"X-Forwarded-Proto\";s:5:\"https\";s:10:\"Connection\";s:5:\"close\";s:6:\"Accept\";s:16:\"application/json\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.28.3\";s:13:\"Postman-Token\";s:36:\"63d5a7d5-52d2-4216-b1d3-37a911d68042\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:6:\"Cookie\";s:112:\"ciarthur_csrf_cookie=4e881729a5e2d93b63947fc26c8be10a; ciarthur_session=14eb9cd5f8b5f784a29b5ee1fdbe0ec9b0efae5b\";}', '', '172.31.0.1', 1632879568, 0.0302699, '0', 403),
(7, 'api/mahasiswa/index', 'get', 'a:10:{s:4:\"Host\";s:13:\"appt.demoo.id\";s:9:\"X-Real-IP\";s:12:\"36.65.98.157\";s:15:\"X-Forwarded-For\";s:12:\"36.65.98.157\";s:17:\"X-Forwarded-Proto\";s:5:\"https\";s:10:\"Connection\";s:5:\"close\";s:6:\"Accept\";s:16:\"application/json\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.28.3\";s:13:\"Postman-Token\";s:36:\"5c8ee36f-f34f-4e54-85f4-500c41650557\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:6:\"Cookie\";s:112:\"ciarthur_csrf_cookie=4e881729a5e2d93b63947fc26c8be10a; ciarthur_session=e9e4c348357e4dda4b5458a628b56857462b229e\";}', '', '172.31.0.1', 1632879582, 0.0271389, '0', 403),
(8, 'api/mahasiswa/index', 'get', 'a:10:{s:4:\"Host\";s:13:\"appt.demoo.id\";s:9:\"X-Real-IP\";s:12:\"36.65.98.157\";s:15:\"X-Forwarded-For\";s:12:\"36.65.98.157\";s:17:\"X-Forwarded-Proto\";s:5:\"https\";s:10:\"Connection\";s:5:\"close\";s:6:\"Accept\";s:16:\"application/json\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.28.3\";s:13:\"Postman-Token\";s:36:\"5026abbb-1513-4a35-8772-e0f6f0458da2\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:6:\"Cookie\";s:112:\"ciarthur_csrf_cookie=4e881729a5e2d93b63947fc26c8be10a; ciarthur_session=e9e4c348357e4dda4b5458a628b56857462b229e\";}', '', '172.31.0.1', 1632879598, 0.839806, '1', 0),
(9, 'api/mahasiswa/index', 'get', 'a:10:{s:4:\"Host\";s:13:\"appt.demoo.id\";s:9:\"X-Real-IP\";s:12:\"36.65.98.157\";s:15:\"X-Forwarded-For\";s:12:\"36.65.98.157\";s:17:\"X-Forwarded-Proto\";s:5:\"https\";s:10:\"Connection\";s:5:\"close\";s:6:\"Accept\";s:16:\"application/json\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.28.3\";s:13:\"Postman-Token\";s:36:\"8821fbff-ad0b-4c73-a330-d615b83de02c\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:6:\"Cookie\";s:112:\"ciarthur_csrf_cookie=4e881729a5e2d93b63947fc26c8be10a; ciarthur_session=e9e4c348357e4dda4b5458a628b56857462b229e\";}', '', '172.31.0.1', 1632879636, 0.18057, '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` bigint(20) NOT NULL,
  `nim` varchar(100) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `angkatan` varchar(5) DEFAULT NULL,
  `prodi_id` varchar(100) DEFAULT NULL,
  `fakultas_id` varchar(100) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `is_active` enum('0','1') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `foto`, `angkatan`, `prodi_id`, `fakultas_id`, `latitude`, `longitude`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 'M3119001', 'Adam Arthur Faizal', '61db6669a2b69c0cbfed473da416843c.png', '2019', '132', '19', '-7.575147884697552', '110.90389251708986', '1', '2021-09-26 13:26:54', 1, '2021-10-21 21:37:17', 1, NULL, NULL),
(3, 'M3119085', 'Tri Wulandari', 'f0c5f7c8100bcf0328440000149f749a.png', '2019', '132', '19', '-7.675717859972691', '110.9689944944753', '1', '2021-10-13 07:30:07', 1, '2021-10-21 21:37:29', 1, NULL, NULL),
(4, 'M3119000', 'Mbah Putih', '43be1a47a298cf38a299e2a742c46456.png', '2019', '138', '9', '-7.574423154803418', '110.90500075460534', '1', '2021-10-14 21:50:06', 1, '2021-10-19 21:36:51', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama` varchar(55) DEFAULT NULL,
  `link` varchar(55) DEFAULT NULL,
  `icon` varchar(55) DEFAULT NULL,
  `has_submenu` enum('0','1') DEFAULT NULL,
  `is_child` enum('0','1') DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `active_code` varchar(55) DEFAULT NULL,
  `urutan` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `message` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `is_active` enum('0','1') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `pengaduan`
--

INSERT INTO `pengaduan` (`id`, `name`, `email`, `phone`, `message`, `created_at`, `is_active`) VALUES
(1, 'Adam Arthur Faizal', 'adam.faizal.af6@student.uns.ac.id', '081234567890', 'Uji coba pengaduan', '2021-09-26 13:28:53', '1');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `perm_key` varchar(55) DEFAULT NULL,
  `perm_name` varchar(55) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `perm_key`, `perm_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'access-dashboard', 'Access Dashboard', '2021-10-11 00:17:27', NULL, NULL),
(4, 'access-mahasiswa', 'Access Mahasiswa', '2021-10-11 02:25:32', NULL, NULL),
(5, 'create-mahasiswa', 'Create Mahasiswa', '2021-10-11 02:26:01', NULL, NULL),
(6, 'update-mahasiswa', 'Update Mahasiswa', '2021-10-11 02:29:17', NULL, NULL),
(7, 'delete-mahasiswa', 'Delete Mahasiswa', '2021-10-11 02:29:52', NULL, NULL),
(8, 'access-pengaduan', 'Access Pengaduan', '2021-10-11 03:42:20', NULL, NULL),
(9, 'access-prodi', 'Access Prodi', '2021-10-12 03:35:42', NULL, NULL),
(10, 'create-prodi', 'Create Prodi', '2021-10-12 03:37:59', NULL, NULL),
(11, 'update-prodi', 'Update Prodi', '2021-10-12 03:42:03', NULL, NULL),
(12, 'delete-prodi', 'Delete Prodi', '2021-10-12 03:49:24', NULL, NULL),
(13, 'access-fakultas', 'Access Fakultas', '2021-10-12 04:22:07', NULL, NULL),
(14, 'create-fakultas', 'Create Fakultas', '2021-10-12 04:26:19', NULL, NULL),
(15, 'update-fakultas', 'Update Fakultas', '2021-10-12 04:26:49', NULL, NULL),
(16, 'delete-fakultas', 'Delete Fakultas', '2021-10-12 04:27:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `id` int(11) NOT NULL,
  `perm_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `value` tinyint(4) DEFAULT 0,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`id`, `perm_id`, `group_id`, `value`, `created_at`, `updated_at`) VALUES
(9, 1, 1, 0, 1633873031, 1633873031),
(10, 2, 1, 1, 1633873031, 1633873031),
(11, 3, 1, 1, 1633911664, 1633911664),
(13, 3, 2, 1, 1633912140, 1633912140),
(14, 3, 1, 1, 1633919430, 1633919430),
(15, 4, 1, 1, 1633919430, 1633919430),
(16, 5, 1, 1, 1633919430, 1633919430),
(17, 6, 1, 1, 1633919430, 1633919430),
(18, 7, 1, 1, 1633919430, 1633919430),
(19, 3, 1, 1, 1633923867, 1633923867),
(20, 4, 1, 1, 1633923867, 1633923867),
(21, 5, 1, 1, 1633923867, 1633923867),
(22, 6, 1, 1, 1633923867, 1633923867),
(23, 7, 1, 1, 1633923867, 1633923867),
(24, 8, 1, 1, 1633923867, 1633923867),
(25, 3, 2, 1, 1634005019, 1634005019),
(26, 4, 2, 1, 1634005019, 1634005019),
(27, 8, 2, 1, 1634005019, 1634005019),
(28, 3, 2, 1, 1634005972, 1634005972),
(29, 4, 2, 1, 1634005972, 1634005972),
(30, 5, 2, 0, 1634005972, 1634005972),
(31, 6, 2, 0, 1634005972, 1634005972),
(32, 7, 2, 0, 1634005972, 1634005972),
(33, 8, 2, 1, 1634005972, 1634005972),
(34, 3, 2, 1, 1634007996, 1634007996),
(35, 4, 2, 1, 1634007996, 1634007996),
(36, 5, 2, 0, 1634007996, 1634007996),
(37, 6, 2, 0, 1634007996, 1634007996),
(38, 7, 2, 0, 1634007996, 1634007996),
(39, 8, 2, 0, 1634007996, 1634007996),
(40, 3, 2, 1, 1634009679, 1634009679),
(41, 4, 2, 1, 1634009679, 1634009679),
(42, 5, 2, 0, 1634009679, 1634009679),
(43, 6, 2, 0, 1634009679, 1634009679),
(44, 7, 2, 0, 1634009679, 1634009679),
(45, 8, 2, 1, 1634009679, 1634009679),
(46, 13, 1, 1, 1634019828, 1634019828),
(47, 14, 1, 1, 1634019828, 1634019828),
(48, 15, 1, 1, 1634019828, 1634019828),
(49, 16, 1, 1, 1634019828, 1634019828),
(50, 3, 1, 1, 1634019850, 1634019850),
(51, 4, 1, 1, 1634019850, 1634019850),
(52, 5, 1, 1, 1634019850, 1634019850),
(53, 6, 1, 1, 1634019850, 1634019850),
(54, 7, 1, 1, 1634019850, 1634019850),
(55, 8, 1, 1, 1634019850, 1634019850),
(56, 9, 1, 1, 1634019850, 1634019850),
(57, 10, 1, 1, 1634019850, 1634019850),
(58, 11, 1, 1, 1634019850, 1634019850),
(59, 12, 1, 1, 1634019850, 1634019850);

-- --------------------------------------------------------

--
-- Table structure for table `permission_user`
--

CREATE TABLE `permission_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `permission_id` int(11) DEFAULT NULL,
  `value` tinyint(4) DEFAULT 0,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `plotting_menu`
--

CREATE TABLE `plotting_menu` (
  `id` int(11) NOT NULL,
  `permission_role_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `type` enum('0','1') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `id` bigint(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `fakultas_id` varchar(100) DEFAULT NULL,
  `is_active` enum('0','1') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id`, `nama`, `fakultas_id`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 'D-3 Bahasa Inggris', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'D-3 Desain Komunikasi Visual', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'D-3 Bahasa Mandarin', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'D-3 Usaha Perjalanan Wisata', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'S-1 Sastra Daerah/Sastra Jawa', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'S-1 Sastra Indonesia', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'S-1 Sastra Inggris', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'S-1 Sastra Inggris (Transfer)', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'S-1 Ilmu Sejarah', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'S-1 Seni Rupa Murni', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'S-1 Desain Komunikasi Visual', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'S-1 Desain Interior', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'S-1 Kriya Seni', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'S-1 Sastra Arab', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'S-1 Ilmu Administrasi Negara', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'S-1 Ilmu Komunikasi', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'S-1 Sosiologi', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'S-1 Hubungan Internasional', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'S-1 Ilmu Administrasi Negara (Transfer)', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'S-1 Ilmu Komunikasi (Transfer)', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'D-3 Manajemen Administrasi', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'D-3 Perpustakaan', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'S-1 Sosiologi (Transfer)', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'S-1 Hukum', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'D-3 Manajemen Bisnis', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'D-3 Manajemen Pemasaran', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'D-3 Manajemen Perdagangan', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(33, 'D-3 Akuntansi', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'D-3 Perpajakan', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'D-3 Keuangan Perbankan', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'S-1 Ekonomi Pembangunan', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'S-1 Ekonomi Pembangunan (Transfer)', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'S-1 Manajemen', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'S-1 Manajemen (Transfer)', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'S-1 Akuntansi', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'S-1 Akuntansi (Transfer)', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(42, 'D-3 Hiperkes dan Keselamatan Kerja', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'D-3 Kebidanan', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(44, 'D-4 Kebidanan', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(46, 'D-4 Keselamatan dan Kesehatan Kerja', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'S-1 Pendidikan Dokter', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(48, 'S-1 Psikologi', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(49, 'Profesi Dokter', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(50, 'D-3 Agribisnis Minat Agrofarmaka', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(51, 'D-3 Teknologi Hasil Pertanian', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(55, 'S-1 Agronomi', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(56, 'S-1 Ilmu Tanah', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(58, 'S-1 Penyuluhan dan Komunikasi Pertanian', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(59, 'S-1 Peternakan', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(60, 'S-1 Teknologi Hasil Pertanian', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(61, 'S-1 Agroteknologi', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(62, 'S-1 Agribisnis', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(63, 'S-1 Ilmu Dan Teknologi Pangan', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(64, 'D-3 Teknik Kimia', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(65, 'S-1 Teknik Sipil', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(66, 'S-1 Arsitektur', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(67, 'S-1 Perencanaan Wilayah dan Kota', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(68, 'S-1 Teknik Mesin', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(69, 'S-1 Teknik Kimia', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(70, 'S-1 Teknik Kimia (Transfer)', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(71, 'S-1 Teknik Industri', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(72, 'S-1 Teknik Industri (Transfer)', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(73, 'S-1 Teknik Sipil (Transfer)', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(77, 'S-1 Bimbingan Konseling', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(78, 'S-1 Pendidikan Luar Biasa', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(79, 'S-1 Pendidikan Bahasa Indonesia', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(80, 'S-1 Pendidikan Bahasa Inggris', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(81, 'S-1 Pendidikan Seni Rupa', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(83, 'S-1 Pendidikan Seni Rupa (PPKHB)', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(84, 'S-1 Pendidikan Matematika', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(85, 'S-1 Pendidikan Fisika', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(86, 'S-1 Pendidikan Kimia', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(87, 'S-1 Pendidikan Biologi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(88, 'S-1 Pendidikan Ekonomi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(89, 'S-1 Pendidikan Administrasi Perkantoran', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(90, 'S1 PENDIDIKAN TATA NIAGA', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(91, 'S-1 Pendidikan Sejarah', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(92, 'S-1 Pendidikan Geografi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(93, 'S-1 PPKN', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(94, 'S-1 Pendidikan Sosiologi - Antropologi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(95, 'S-1 Pendidikan Teknik Bangunan', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(96, 'S-1 Pendidikan Teknik Mesin', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(97, 'S-1 Pendidikan Jasmani Kesehatan', '18', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(98, 'S-1 Pendidikan Kepelatihan dan Olahraga', '18', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(100, 'S-1 PGSD', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(101, 'S-1 PAUD', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(104, 'S-1 Pendidikan Kimia (Transfer)', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(106, 'S-1 Pendidikan Bahasa Jawa', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(116, 'S-1 Pendidikan Teknik Informatika dan Komputer', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(121, 'S-1 Pendidikan Jasmani Kesehatan (PPKHB)', '18', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(123, 'S-1 Pendidikan Luar Biasa (PPKHB)', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(132, 'D-3 Teknik Informatika', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(133, 'D-3 Farmasi', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(134, 'S-1 Matematika', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(135, 'S-1 Fisika', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(136, 'S-1 Kimia', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(137, 'S-1 Biologi', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(138, 'S-1 Informatika', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(141, 'S-1 Farmasi', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(144, 'S-2 Ilmu Keolahragaan', '18', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(145, 'S-2 Linguistik Deskriptif', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(146, 'S-2 Linguistik Penerjemahan', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(147, 'S-2 Ilmu Komunikasi (Riset dan Pengembangan Ilmu Komunikasi)', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(148, 'S-2 Ilmu Komunikasi (Menejemen Komunikasi)', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(149, 'S-2 Magister Administrasi Publik', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(150, 'S-2 Sosiologi', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(151, 'S-2 Kedokteran Keluarga : Pend. Profesi', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(154, 'S-2 Kedokteran Keluarga', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(155, 'S-2 Agronomi', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(158, 'S-2 Agribisnis', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(160, 'S-2 Teknologi Pendidikan', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(161, 'S-2 Pend Kepedudukan dan Lingkungan Hidup', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(162, 'S-2 Pendidikan Sains Minat Fisika', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(163, 'S-2 Pendidikan Sains Minat Biologi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(164, 'S-2 Pendidikan Sains Minat IPA', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(165, 'S-2 Pendidikan Sains Minat Kimia', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(166, 'S-2 Pendidikan Bahasa Indonesia', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(167, 'S-2 Pendidikan Bahasa Jawa', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(168, 'S-2 Pendidikan Matematika', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(169, 'S-2 Pendidikan Sejarah', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(170, 'S-2 Pendidikan Geografi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(171, 'S-2 Pendidikan Bahasa Inggris', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(172, 'S-2 Biosains', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(173, 'S-2 Ilmu Fisika', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(174, 'S-2 Teknik Sipil', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(175, 'S-2 Teknik Mesin', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(176, 'S-2 Pendidikan Ekonomi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(177, 'S-2 Kedokteran Keluarga : Ilmu Biomedik', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(178, 'S-2 Seni Rupa', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(179, 'S-2 Kenotariatan', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(180, 'S-3 Linguistik Deskriptif', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(181, 'S-3 Linguistik Pragmatik', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(182, 'S-3 Linguistik Penerjemahan', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(183, 'S-3 Ilmu Pertanian', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(187, 'S-3 Pendidikan Bahasa Indonesia', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(194, 'S-3 Ilmu Pendidikan', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(195, 'S-3 Ilmu Kedokteran', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(204, 'D-3 TEKNIK SIPIL', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(219, 'S-1 Pendidikan Akuntansi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(221, 'D-3 Hubungan Masyarakat', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(222, 'D-3 Penyiaran', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(223, 'D-3 Periklanan', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(225, 'Pendidikan Profesi Akuntansi', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(226, 'S-1 PGSD Kebumen', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(242, 'S-2 Magister Akuntansi', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(248, 'S-2 Ekonomi Pembangunan', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(256, 'S-2 Magister Manajemen', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(260, 'S-3 Ilmu Ekonomi', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(266, 'S-3 Pendidikan IPA', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(275, 'D-3 Teknik Mesin Produksi', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(283, 'D-3 Teknik Mesin', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(284, 'S-1 Teknik Mesin (Transfer)', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(285, 'D-3 Agribisnis', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(286, 'D-3 Agribisnis Minat Hortikultura', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(290, 'S2 Ilmu Hukum', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(291, 'S-3 Hukum', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(321, 'S-1 Teknik Elektro', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(325, 'D-3 Teknik Sipil Bangunan Gedung', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(326, 'D-3 Teknik Sipil Transportasi', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(328, 'D-3 Teknik Sipil Infra Struktur Perkotaan', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(329, 'S-2 Pendidikan Bahasa dan Sastra Daerah', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(330, 'S-2 Pendidikan Seni', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(331, 'S-2 Pendidikan Luar Biasa', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(332, 'S-2 PGSD', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(348, 'S-2 Teknik Industri', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(354, 'S-1 Statistika', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(382, 'S-2 Kimia', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(388, 'S-2 Teknik Kimia', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(397, 'S-1 Pendidikan Ilmu Pengetahuan Alam', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(398, 'S-1 Hukum Minat Hukum Administrasi Negara', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(399, 'D-3 Teknik Mesin Industri', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(400, 'S-1 Agronomi (Transfer)', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(401, 'S-1 Agribisnis (Transfer)', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(402, 'S-1 Pendidikan Bahasa Indonesia (Transfer)', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(403, 'S-1 Pendidikan Bahasa Inggris (Transfer)', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(404, 'S-1 Pendidikan Jasmani Kesehatan (Transfer)', '18', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(405, 'PPDS Kulit dan Kelamin', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(406, 'S-1 Hukum Minat Hukum Internasional', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(407, 'S-1 Hukum Minat Hukum dan Masyarakat', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(408, 'S-1 Hukum Minat Hukum Tata Negara', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(409, 'S-2 Ilmu Hukum Minat Bisnis', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(410, 'S-1 Ilmu Dan Teknologi Pangan (Transfer)', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(411, 'PPDS Radiologi', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(412, 'S-2 Teknik Mesin (Asing)', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(413, 'PPDS Anestesiologi dan Reanimasi', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(414, 'PPDS Ilmu Kesehatan THT - KL', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(415, 'S-1 Pendidikan Matematika (Transfer)', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(416, 'PPDS Pulomonologi dan Ilmu Kedokteran Respirasi', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(417, 'PPDS Ilmu Kesehatan Anak', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(418, 'PPDS Obstetri dan Ginekologi', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(419, 'PPDS Ilmu Pendidikan Jiwa (Psikiatri)', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(420, 'PPDS Ilmu Bedah', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(421, 'PPDS Ilmu Penyakit Saraf (Neurologi)', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(423, 'PPDS Ilmu Penyakit Jantung (Kardiologi dan Kedokteran Vaskuler)', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(424, 'S-1 PGSD Solo (Transfer)', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(425, 'S-1 PGSD Kebumen (Transfer)', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(426, 'PPDS Patologi Klinik', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(427, 'PPDS Orthopaedi dan Traumatologi', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(429, 'S-2 Pendidikan Sains', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(433, 'S-2 Ilmu Hukum Minat Ekonomi Islam', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(434, 'S-2 Ilmu Hukum Minat Kesehatan', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(435, 'S-1 Pendidikan Kepelatihan dan Olahraga (Transfer)', '18', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(436, 'S-1 Hukum Minat Hukum Acara', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(437, 'S-1 Hukum Minat Hukum Pidana', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(438, 'S-2 Pendidikan Biologi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(439, 'D-2 Teknologi Hasil Pertanian K. Kab. Madiun', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(440, 'D-2 Teknik Mesin K. Kab. Madiun', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(441, 'D-2 Teknik Informatika K. Kab. Madiun', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(442, 'S-2 Pendidikan Fisika', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(443, 'S-2 Pendidikan IPA', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(444, 'PPDS Penyakit Dalam', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(445, 'S-3 Teknik Sipil', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(446, 'S-1 Pendidikan OR & Rekreasi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(447, 'S-1 Hukum Minat Hukum Perdata', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(448, 'S-2 Magister Manajemen (Asing)', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(449, 'S-1 Pendidikan Ekonomi (Transfer)', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(450, 'S-3 Ilmu Keolahragaan', '18', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(451, 'S-2 Magister Akuntansi (Asing)', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(453, 'S-3 Ilmu Komunikasi', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(454, 'S-2 Pendidikan Kimia', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(455, 'S-3 Teknik Mesin', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(457, 'S-2 Ilmu Hukum Minat Keb. Publik', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(462, 'Pendidikan Profesi Guru SD', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(463, 'Pendidikan Profesi Guru SMK', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(464, 'S-2 Pendidikan PPKN', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(465, 'S-2 Teknik Arsitektur', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(471, 'S1 PENDIDIKAN DOKTER (Kurikulum Lama)', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(474, 'S-2 Ilmu Tanah', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(475, 'D-4 Studi Demografi dan Pencatatan Sipil', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(476, 'S-1 Ilmu Lingkungan', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(477, 'Profesi Insinyur', '7', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(478, 'S-2 Pendidikan Guru Vokasi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(479, 'S-3 Biologi', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(480, 'S-3 Fisika', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(481, 'S-3 Ilmu Ekonomi (Asing)', '4', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(482, 'S-3 Pendidikan Sejarah', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(483, 'S-2 Peternakan', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(484, 'S-1 Pendidikan Sosiologi - Antropologi (Transfer)', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(485, 'S-1 Pengelolaan Hutan', '6', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(486, 'D-3 Budidaya Ternak', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(487, 'S-3 Pendidikan Ekonomi', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(488, 'S-2 Hukum', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(489, 'S-2 Ilmu Hukum', '3', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(490, 'Pendidikan Profesi Guru', '8', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(491, 'D-3 Akuntansi (PSDKU)', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(492, 'D-3 Komunikasi Terapan', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(493, 'D-3 Teknik Informatika (PSDKU)', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(494, 'D-3 Teknik Sipil', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(495, 'D-3 Teknologi Hasil Pertanian (PSDKU)', '19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(500, 'S-2 Linguistik', '16', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(501, 'S-2 Ilmu Komunikasi', '2', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(502, 'Coba ini baru bener', '7', '0', '2021-10-13 02:59:27', 1, '2021-10-13 03:15:13', 1, '2021-10-13 03:15:20', 1),
(503, 'Coba lagi lagi', '19', '0', '2021-10-13 03:14:26', 1, NULL, NULL, '2021-10-13 03:15:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `is_active` enum('0','1') DEFAULT '1',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'Administrator', '1', '2021-10-09 01:21:00', NULL, NULL),
(2, 'member', 'Member', '1', '2021-10-09 01:21:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `role_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `user_id`, `role_id`) VALUES
(14, 1, 1),
(4, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `level` int(2) DEFAULT NULL,
  `ip_addresses` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `user_id`, `token`, `level`, `ip_addresses`, `is_active`, `created_at`) VALUES
(1, NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ', NULL, '172.31.0.1', 0, 1634129498),
(2, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ', NULL, '172.31.0.1', 0, 1634129568),
(3, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ', NULL, '172.31.0.1', 0, 1634129855),
(4, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ', NULL, '172.31.0.1', 0, 1634129867),
(5, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ', NULL, '172.31.0.1', 0, 1634130049),
(6, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ', NULL, '172.31.0.1', 0, 1634130154),
(7, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ', NULL, '172.31.0.1', 0, 1634130736),
(8, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ', NULL, '172.31.0.1', 0, 1634131549),
(9, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ', NULL, '172.31.0.1', 0, 1634131654),
(10, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ', NULL, '172.31.0.1', 0, 1634131776);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'administrator', '$argon2i$v=19$m=65536,t=4,p=1$RmhCLmxSaGdKOHh0bTVEMA$vD2jQnfQ5jkaC+IUHdPD9Ob6WcyDRFwYmoEYRYjECIw', 'admin@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1634818915, 1, 'Admin', '-', 'PHICOS', '081234567890'),
(2, '172.31.0.1', 'operator', '$argon2i$v=19$m=65536,t=4,p=1$VTk3LnNFV0dJMHRxaEUyWA$9hbGtZs3hFJJWXXIBvB4el/PrtzkSvoKNQfdKHZclvo', 'operator@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1626873123, 1634122788, 1, 'operator', 'operator', 'operator', '081234567890');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`,`ip_address`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `limits`
--
ALTER TABLE `limits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `permission_id` (`permission_id`);

--
-- Indexes for table `plotting_menu`
--
ALTER TABLE `plotting_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`role_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`role_id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_email` (`email`),
  ADD UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  ADD UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  ADD UNIQUE KEY `uc_remember_selector` (`remember_selector`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `limits`
--
ALTER TABLE `limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `permission_user`
--
ALTER TABLE `permission_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plotting_menu`
--
ALTER TABLE `plotting_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=504;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
