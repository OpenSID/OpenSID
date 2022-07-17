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

-- Dumping structure for table sid.tweb_cara_kb
CREATE TABLE IF NOT EXISTS `tweb_cara_kb` (
  `id` tinyint(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `sex` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sid.tweb_cara_kb: ~8 rows (approximately)
DELETE FROM `tweb_cara_kb`;
/*!40000 ALTER TABLE `tweb_cara_kb` DISABLE KEYS */;
INSERT INTO `tweb_cara_kb` (`id`, `nama`, `sex`) VALUES
	(1, 'Pil', 2),
	(2, 'IUD', 2),
	(3, 'Suntik', 2),
	(4, 'Kondom', 1),
	(5, 'Susuk KB', 2),
	(6, 'Sterilisasi Wanita', 2),
	(7, 'Sterilisasi Pria', 1),
	(99, 'Lainnya', 3),
	(100, 'Tidak Menggunakan', 3);
/*!40000 ALTER TABLE `tweb_cara_kb` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
