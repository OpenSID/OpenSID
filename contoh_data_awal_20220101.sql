-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table opensid.tweb_rtm
CREATE TABLE IF NOT EXISTS `tweb_rtm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik_kepala` int(11) NOT NULL,
  `no_kk` varchar(30) NOT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `kelas_sosial` int(11) DEFAULT NULL,
  `no_rumah` int(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_kk` (`no_kk`),
  UNIQUE KEY `no_kk_2` (`no_kk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table opensid.tweb_rtm: ~2 rows (approximately)
/*!40000 ALTER TABLE `tweb_rtm` DISABLE KEYS */;
INSERT INTO `tweb_rtm` (`id`, `nik_kepala`, `no_kk`, `tgl_daftar`, `kelas_sosial`, `no_rumah`) VALUES
	(1, 1, '011405000012', '2020-07-30 04:36:37', NULL, NULL),
	(2, 4, '011405000013', '2022-01-30 13:21:55', NULL, NULL);
/*!40000 ALTER TABLE `tweb_rtm` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
