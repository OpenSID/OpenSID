
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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agenda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_artikel` int NOT NULL,
  `tgl_agenda` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `koordinator_kegiatan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `lokasi_kegiatan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agenda_config_fk` (`config_id`),
  KEY `id_artikel_fk` (`id_artikel`),
  CONSTRAINT `agenda_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_artikel_fk` FOREIGN KEY (`id_artikel`) REFERENCES `artikel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alias_kodeisian` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `judul` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `alias` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `content` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias_kodeisian_config_id_judul_alias_unique` (`config_id`,`judul`,`alias`),
  CONSTRAINT `alias_kodeisian_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_indikator` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_master` int DEFAULT NULL,
  `nomor` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pertanyaan` varchar(400) COLLATE utf8mb4_general_ci NOT NULL,
  `id_tipe` int DEFAULT NULL,
  `bobot` tinyint NOT NULL DEFAULT '0',
  `act_analisis` tinyint NOT NULL DEFAULT '2',
  `id_kategori` int DEFAULT NULL,
  `is_publik` tinyint(1) NOT NULL DEFAULT '0',
  `is_teks` tinyint(1) NOT NULL DEFAULT '0',
  `referensi` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `analisis_indikator_id_master_id_tipe_index` (`id_master`,`id_tipe`),
  KEY `analisis_indikator_config_fk` (`config_id`),
  KEY `analisis_indikator_id_tipe_index` (`id_tipe`),
  KEY `analisis_indikator_id_kategori_index` (`id_kategori`),
  CONSTRAINT `analisis_indikator_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_indikator_id_kategori_fk` FOREIGN KEY (`id_kategori`) REFERENCES `analisis_kategori_indikator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_indikator_master_fk` FOREIGN KEY (`id_master`) REFERENCES `analisis_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_indikator_tipe_fk` FOREIGN KEY (`id_tipe`) REFERENCES `analisis_tipe_indikator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_kategori_indikator` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_master` int DEFAULT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori_kode` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `analisis_kategori_indikator_config_fk` (`config_id`),
  KEY `id_master` (`id_master`),
  CONSTRAINT `analisis_kategori_indikator_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_kategori_indikator_master_fk` FOREIGN KEY (`id_master`) REFERENCES `analisis_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_klasifikasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_master` int DEFAULT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `minval` double(7,2) NOT NULL,
  `maxval` double(7,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `analisis_klasifikasi_config_fk` (`config_id`),
  KEY `id_master` (`id_master`),
  CONSTRAINT `analisis_klasifikasi_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_klasifikasi_master_fk` FOREIGN KEY (`id_master`) REFERENCES `analisis_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_master` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `subjek_tipe` int DEFAULT NULL,
  `lock` tinyint(1) NOT NULL DEFAULT '1',
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `kode_analisis` varchar(5) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '00000',
  `id_kelompok` int DEFAULT NULL,
  `pembagi` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '100',
  `id_child` smallint DEFAULT NULL,
  `format_impor` tinyint DEFAULT NULL,
  `jenis` tinyint NOT NULL DEFAULT '2',
  `gform_id` text COLLATE utf8mb4_general_ci,
  `gform_nik_item_id` text COLLATE utf8mb4_general_ci,
  `gform_last_sync` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `analisis_master_config_fk` (`config_id`),
  KEY `analisis_master_subjek_fk` (`subjek_tipe`),
  CONSTRAINT `analisis_master_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_master_subjek_fk` FOREIGN KEY (`subjek_tipe`) REFERENCES `analisis_ref_subjek` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_parameter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_indikator` int DEFAULT NULL,
  `jawaban` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `nilai` int NOT NULL DEFAULT '0',
  `kode_jawaban` int DEFAULT '0',
  `asign` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `analisis_parameter_config_fk` (`config_id`),
  KEY `id_indikator` (`id_indikator`),
  CONSTRAINT `analisis_parameter_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_parameter_indikator_fk` FOREIGN KEY (`id_indikator`) REFERENCES `analisis_indikator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_partisipasi` (
  `id_subjek` int DEFAULT NULL,
  `id_master` int DEFAULT NULL,
  `id_periode` int DEFAULT NULL,
  `id_klassifikasi` int DEFAULT NULL,
  `config_id` int DEFAULT NULL,
  KEY `id_subjek` (`id_subjek`,`id_master`,`id_periode`,`id_klassifikasi`),
  KEY `id_master` (`id_master`),
  KEY `id_periode` (`id_periode`),
  KEY `id_klassifikasi` (`id_klassifikasi`),
  KEY `analisis_partisipasi_config_id_foreign` (`config_id`),
  CONSTRAINT `analisis_partisipasi_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_partisipasi_klasifikasi_fk` FOREIGN KEY (`id_klassifikasi`) REFERENCES `analisis_klasifikasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_partisipasi_master_fk` FOREIGN KEY (`id_master`) REFERENCES `analisis_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_partisipasi_periode_fk` FOREIGN KEY (`id_periode`) REFERENCES `analisis_periode` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_partisipasi_subjek_fk` FOREIGN KEY (`id_subjek`) REFERENCES `analisis_parameter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_periode` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_master` int DEFAULT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_state` int DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun_pelaksanaan` year NOT NULL,
  PRIMARY KEY (`id`),
  KEY `analisis_periode_config_fk` (`config_id`),
  KEY `id_master` (`id_master`),
  KEY `id_state` (`id_state`),
  CONSTRAINT `analisis_periode_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_periode_master_fk` FOREIGN KEY (`id_master`) REFERENCES `analisis_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `state_fk` FOREIGN KEY (`id_state`) REFERENCES `analisis_ref_state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_ref_state` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_ref_subjek` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subjek` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_respon` (
  `id_indikator` int DEFAULT NULL,
  `config_id` int NOT NULL,
  `id_parameter` int DEFAULT NULL,
  `id_subjek` int DEFAULT NULL,
  `id_periode` int DEFAULT NULL,
  `penduduk_id` int DEFAULT NULL,
  `keluarga_id` int DEFAULT NULL,
  `kelompok_id` int DEFAULT NULL,
  `rtm_id` int DEFAULT NULL,
  `desa_id` int DEFAULT NULL,
  `dusun_id` int DEFAULT NULL,
  `rw_id` int DEFAULT NULL,
  `rt_id` int DEFAULT NULL,
  KEY `id_parameter` (`id_parameter`,`id_subjek`),
  KEY `id_indikator` (`id_indikator`),
  KEY `analisis_respon_config_fk` (`config_id`),
  KEY `id_periode` (`id_periode`),
  KEY `analisis_respon_penduduk_id_foreign` (`penduduk_id`),
  KEY `analisis_respon_keluarga_id_foreign` (`keluarga_id`),
  KEY `analisis_respon_kelompok_id_foreign` (`kelompok_id`),
  KEY `analisis_respon_rtm_id_foreign` (`rtm_id`),
  KEY `analisis_respon_desa_id_foreign` (`desa_id`),
  KEY `analisis_respon_dusun_id_foreign` (`dusun_id`),
  KEY `analisis_respon_rw_id_foreign` (`rw_id`),
  KEY `analisis_respon_rt_id_foreign` (`rt_id`),
  CONSTRAINT `analisis_respon_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_dusun_id_foreign` FOREIGN KEY (`dusun_id`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_indikator_fk` FOREIGN KEY (`id_indikator`) REFERENCES `analisis_indikator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_kelompok_id_foreign` FOREIGN KEY (`kelompok_id`) REFERENCES `kelompok` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_keluarga_id_foreign` FOREIGN KEY (`keluarga_id`) REFERENCES `tweb_keluarga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_parameter_fk` FOREIGN KEY (`id_parameter`) REFERENCES `analisis_parameter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_periode_fk` FOREIGN KEY (`id_periode`) REFERENCES `analisis_periode` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_rt_id_foreign` FOREIGN KEY (`rt_id`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_rtm_id_foreign` FOREIGN KEY (`rtm_id`) REFERENCES `tweb_rtm` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_rw_id_foreign` FOREIGN KEY (`rw_id`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_respon_bukti` (
  `id_master` int DEFAULT NULL,
  `config_id` int NOT NULL,
  `id_periode` int DEFAULT NULL,
  `id_subjek` int DEFAULT NULL,
  `pengesahan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `penduduk_id` int DEFAULT NULL,
  `keluarga_id` int DEFAULT NULL,
  `kelompok_id` int DEFAULT NULL,
  `rtm_id` int DEFAULT NULL,
  `desa_id` int DEFAULT NULL,
  `dusun_id` int DEFAULT NULL,
  `rw_id` int DEFAULT NULL,
  `rt_id` int DEFAULT NULL,
  KEY `analisis_respon_bukti_master_fk` (`id_master`),
  KEY `analisis_respon_bukti_config_fk` (`config_id`),
  KEY `analisis_respon_bukti_periode_fk` (`id_periode`),
  KEY `analisis_respon_bukti_penduduk_id_foreign` (`penduduk_id`),
  KEY `analisis_respon_bukti_keluarga_id_foreign` (`keluarga_id`),
  KEY `analisis_respon_bukti_kelompok_id_foreign` (`kelompok_id`),
  KEY `analisis_respon_bukti_rtm_id_foreign` (`rtm_id`),
  KEY `analisis_respon_bukti_desa_id_foreign` (`desa_id`),
  KEY `analisis_respon_bukti_dusun_id_foreign` (`dusun_id`),
  KEY `analisis_respon_bukti_rw_id_foreign` (`rw_id`),
  KEY `analisis_respon_bukti_rt_id_foreign` (`rt_id`),
  CONSTRAINT `analisis_respon_bukti_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_bukti_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_bukti_dusun_id_foreign` FOREIGN KEY (`dusun_id`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_bukti_kelompok_id_foreign` FOREIGN KEY (`kelompok_id`) REFERENCES `kelompok` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_bukti_keluarga_id_foreign` FOREIGN KEY (`keluarga_id`) REFERENCES `tweb_keluarga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_bukti_master_fk` FOREIGN KEY (`id_master`) REFERENCES `analisis_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_bukti_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_bukti_periode_fk` FOREIGN KEY (`id_periode`) REFERENCES `analisis_periode` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_bukti_rt_id_foreign` FOREIGN KEY (`rt_id`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_bukti_rtm_id_foreign` FOREIGN KEY (`rtm_id`) REFERENCES `tweb_rtm` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_bukti_rw_id_foreign` FOREIGN KEY (`rw_id`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_respon_hasil` (
  `id_master` int DEFAULT NULL,
  `config_id` int NOT NULL,
  `id_periode` int DEFAULT NULL,
  `id_subjek` int DEFAULT NULL,
  `akumulasi` double(8,3) NOT NULL,
  `tgl_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `penduduk_id` int DEFAULT NULL,
  `keluarga_id` int DEFAULT NULL,
  `kelompok_id` int DEFAULT NULL,
  `rtm_id` int DEFAULT NULL,
  `desa_id` int DEFAULT NULL,
  `dusun_id` int DEFAULT NULL,
  `rw_id` int DEFAULT NULL,
  `rt_id` int DEFAULT NULL,
  UNIQUE KEY `id_master` (`id_master`,`id_periode`,`id_subjek`),
  KEY `analisis_respon_hasil_config_fk` (`config_id`),
  KEY `analisis_respon_hasil_periode_fk` (`id_periode`),
  KEY `analisis_respon_hasil_penduduk_id_foreign` (`penduduk_id`),
  KEY `analisis_respon_hasil_keluarga_id_foreign` (`keluarga_id`),
  KEY `analisis_respon_hasil_kelompok_id_foreign` (`kelompok_id`),
  KEY `analisis_respon_hasil_rtm_id_foreign` (`rtm_id`),
  KEY `analisis_respon_hasil_desa_id_foreign` (`desa_id`),
  KEY `analisis_respon_hasil_dusun_id_foreign` (`dusun_id`),
  KEY `analisis_respon_hasil_rw_id_foreign` (`rw_id`),
  KEY `analisis_respon_hasil_rt_id_foreign` (`rt_id`),
  CONSTRAINT `analisis_respon_hasil_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_hasil_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_hasil_dusun_id_foreign` FOREIGN KEY (`dusun_id`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_hasil_kelompok_id_foreign` FOREIGN KEY (`kelompok_id`) REFERENCES `kelompok` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_hasil_keluarga_id_foreign` FOREIGN KEY (`keluarga_id`) REFERENCES `tweb_keluarga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_hasil_master_fk` FOREIGN KEY (`id_master`) REFERENCES `analisis_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_hasil_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_hasil_periode_fk` FOREIGN KEY (`id_periode`) REFERENCES `analisis_periode` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_hasil_rt_id_foreign` FOREIGN KEY (`rt_id`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_hasil_rtm_id_foreign` FOREIGN KEY (`rtm_id`) REFERENCES `tweb_rtm` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `analisis_respon_hasil_rw_id_foreign` FOREIGN KEY (`rw_id`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_tipe_indikator` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipe` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggota_grup_kontak` (
  `id_grup_kontak` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_grup` int NOT NULL,
  `id_kontak` int DEFAULT NULL,
  `id_penduduk` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_grup_kontak`),
  KEY `anggota_grup_kontak_config_fk` (`config_id`),
  KEY `anggota_grup_kontak_ke_kontak_grup` (`id_grup`),
  KEY `anggota_grup_kontak_ke_kontak` (`id_kontak`),
  KEY `id_penduduk` (`id_penduduk`),
  CONSTRAINT `anggota_grup_kontak_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `anggota_grup_kontak_id_penduduk_fk` FOREIGN KEY (`id_penduduk`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `anggota_grup_kontak_ke_kontak` FOREIGN KEY (`id_kontak`) REFERENCES `kontak` (`id_kontak`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `anggota_grup_kontak_ke_kontak_grup` FOREIGN KEY (`id_grup`) REFERENCES `kontak_grup` (`id_grup`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anjungan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `config_id` int NOT NULL,
  `ip_address` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keyboard` tinyint(1) DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `permohonan_surat_tanpa_akun` tinyint(1) NOT NULL DEFAULT '0',
  `orientasi_layar` tinyint(1) NOT NULL DEFAULT '1',
  `status_alasan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mac_address` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `printer_ip` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `printer_port` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_pengunjung` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `anjungan_uuid_unique` (`uuid`),
  KEY `anjungan_config_fk` (`config_id`),
  CONSTRAINT `anjungan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anjungan_menu` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `icon` text COLLATE utf8mb4_general_ci,
  `link` text COLLATE utf8mb4_general_ci NOT NULL,
  `link_tipe` tinyint NOT NULL,
  `urut` tinyint NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `anjungan_menu_config_fk` (`config_id`),
  CONSTRAINT `anjungan_menu_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `area` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `path` text COLLATE utf8mb4_general_ci,
  `enabled` int NOT NULL DEFAULT '1',
  `ref_polygon` int NOT NULL,
  `foto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_cluster` int DEFAULT NULL,
  `desk` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `area_config_fk` (`config_id`),
  KEY `area_cluster_fk` (`id_cluster`),
  CONSTRAINT `area_cluster_fk` FOREIGN KEY (`id_cluster`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `area_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `artikel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `gambar` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `enabled` int NOT NULL DEFAULT '1',
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_kategori` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `judul` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `headline` tinyint(1) NOT NULL DEFAULT '0',
  `gambar1` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gambar2` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gambar3` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dokumen` varchar(400) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link_dokumen` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `boleh_komentar` tinyint(1) NOT NULL DEFAULT '1',
  `slug` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hit` int DEFAULT '0',
  `tampilan` tinyint DEFAULT '1',
  `slider` tinyint(1) NOT NULL DEFAULT '0',
  `tipe` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'dinamis',
  `urut` int DEFAULT NULL,
  `jenis_widget` tinyint NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`),
  KEY `artikel_config_fk` (`config_id`),
  KEY `artikel_kategori_fk` (`id_kategori`),
  KEY `artikel_kategori_id_user_fk` (`id_user`),
  CONSTRAINT `artikel_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `artikel_kategori_fk` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `artikel_kategori_id_user_fk` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buku_keperluan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `keperluan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `buku_keperluan_config_fk` (`config_id`),
  CONSTRAINT `buku_keperluan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buku_kepuasan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_nama` int DEFAULT NULL,
  `id_pertanyaan` int DEFAULT NULL,
  `id_jawaban` int NOT NULL,
  `pertanyaan_statis` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `buku_kepuasan_config_fk` (`config_id`),
  KEY `buku_kepuasan_nama_fk` (`id_nama`),
  KEY `buku_kepuasan_pertanyaan_fk` (`id_pertanyaan`),
  CONSTRAINT `buku_kepuasan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `buku_kepuasan_nama_fk` FOREIGN KEY (`id_nama`) REFERENCES `buku_tamu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `buku_kepuasan_pertanyaan_fk` FOREIGN KEY (`id_pertanyaan`) REFERENCES `buku_pertanyaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buku_pertanyaan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `pertanyaan` text COLLATE utf8mb4_general_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `buku_pertanyaan_config_fk` (`config_id`),
  CONSTRAINT `buku_pertanyaan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buku_tamu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `instansi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` tinyint(1) NOT NULL DEFAULT '1',
  `alamat` text COLLATE utf8mb4_general_ci,
  `bidang` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keperluan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0: Baru, 1: Selesai',
  `foto` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `buku_tamu_config_fk` (`config_id`),
  CONSTRAINT `buku_tamu_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bulanan_anak` (
  `id_bulanan_anak` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `posyandu_id` int DEFAULT NULL,
  `kia_id` int DEFAULT NULL,
  `status_gizi` tinyint(1) NOT NULL,
  `umur_bulan` tinyint NOT NULL,
  `status_tikar` tinyint(1) NOT NULL,
  `pemberian_imunisasi_dasar` tinyint(1) NOT NULL,
  `pemberian_imunisasi_campak` tinyint(1) DEFAULT NULL,
  `pengukuran_berat_badan` tinyint(1) NOT NULL,
  `berat_badan` double DEFAULT NULL,
  `pengukuran_tinggi_badan` tinyint(1) NOT NULL,
  `tinggi_badan` double DEFAULT NULL,
  `konseling_gizi_ayah` tinyint(1) NOT NULL,
  `konseling_gizi_ibu` tinyint(1) NOT NULL,
  `kunjungan_rumah` tinyint(1) NOT NULL,
  `air_bersih` tinyint(1) NOT NULL,
  `kepemilikan_jamban` tinyint(1) NOT NULL,
  `akta_lahir` tinyint(1) NOT NULL,
  `jaminan_kesehatan` tinyint(1) NOT NULL,
  `pengasuhan_paud` tinyint(1) NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id_bulanan_anak`),
  KEY `bulanan_anak_config_fk` (`config_id`),
  KEY `bulanan_anak_posyandu_fk` (`posyandu_id`),
  KEY `bulanan_anak_kia_fk` (`kia_id`),
  CONSTRAINT `bulanan_anak_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bulanan_anak_kia_fk` FOREIGN KEY (`kia_id`) REFERENCES `kia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bulanan_anak_posyandu_fk` FOREIGN KEY (`posyandu_id`) REFERENCES `posyandu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cdesa` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nomor` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_kepemilikan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_pemilik` tinyint(1) NOT NULL DEFAULT '0',
  `nik_pemilik_luar` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_pemilik_luar` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_pemilik_luar` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor` (`nomor`),
  KEY `cdesa_config_fk` (`config_id`),
  CONSTRAINT `cdesa_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cdesa_penduduk` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_cdesa` int unsigned NOT NULL,
  `id_pend` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cdesa_penduduk_config_fk` (`config_id`),
  KEY `id_cdesa` (`id_cdesa`),
  KEY `cdesa_penduduk_pend_fk` (`id_pend`),
  CONSTRAINT `cdesa_penduduk_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cdesa_penduduk_fk` FOREIGN KEY (`id_cdesa`) REFERENCES `cdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cdesa_penduduk_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `app_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `nama_desa` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_desa` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_desa_bps` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_pos` int DEFAULT NULL,
  `nama_kecamatan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_kecamatan` varchar(6) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_kepala_camat` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nip_kepala_camat` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_kabupaten` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_kabupaten` varchar(4) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_propinsi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_propinsi` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lat` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lng` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zoom` tinyint DEFAULT NULL,
  `map_tipe` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `path` longtext COLLATE utf8mb4_general_ci,
  `alamat_kantor` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_desa` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telepon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nomor_operator` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `website` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kantor_desa` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `warna` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `border` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  `nama_kontak` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hp_kontak` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jabatan_kontak` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_key` (`app_key`),
  UNIQUE KEY `kode_desa` (`kode_desa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `covid19_pantau` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_pemudik` int DEFAULT NULL,
  `tanggal_jam` datetime DEFAULT NULL,
  `suhu_tubuh` decimal(4,2) DEFAULT NULL,
  `batuk` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `flu` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sesak_nafas` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keluhan_lain` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_covid` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `covid19_pantau_config_fk` (`config_id`),
  KEY `fk_pantau_pemudik` (`id_pemudik`),
  CONSTRAINT `covid19_pantau_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pantau_pemudik` FOREIGN KEY (`id_pemudik`) REFERENCES `covid19_pemudik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `covid19_pemudik` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_terdata` int DEFAULT NULL,
  `pantau` tinyint(1) NOT NULL DEFAULT '1',
  `tanggal_datang` date DEFAULT NULL,
  `asal_mudik` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `durasi_mudik` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tujuan_mudik` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keluhan_kesehatan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_covid` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_wajib_pantau` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `covid19_pemudik_config_fk` (`config_id`),
  KEY `fk_pemudik_penduduk` (`id_terdata`),
  CONSTRAINT `covid19_pemudik_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pemudik_penduduk` FOREIGN KEY (`id_terdata`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `covid19_vaksin` (
  `id_penduduk` int NOT NULL,
  `config_id` int NOT NULL,
  `vaksin_1` int DEFAULT NULL,
  `tgl_vaksin_1` date DEFAULT NULL,
  `dokumen_vaksin_1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_vaksin_1` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vaksin_2` int DEFAULT NULL,
  `tgl_vaksin_2` date DEFAULT NULL,
  `dokumen_vaksin_2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_vaksin_2` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vaksin_3` int DEFAULT NULL,
  `tgl_vaksin_3` date DEFAULT NULL,
  `dokumen_vaksin_3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_vaksin_3` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tunda` int DEFAULT NULL,
  `keterangan` tinytext COLLATE utf8mb4_general_ci,
  `surat_dokter` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_penduduk`),
  KEY `covid19_vaksin_config_fk` (`config_id`),
  CONSTRAINT `covid19_vaksin_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disposisi_surat_masuk` (
  `id_disposisi` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_surat_masuk` int NOT NULL,
  `id_desa_pamong` int DEFAULT NULL,
  `disposisi_ke` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_disposisi`),
  KEY `disposisi_surat_masuk_config_fk` (`config_id`),
  KEY `id_surat_fk` (`id_surat_masuk`),
  KEY `desa_pamong_fk` (`id_desa_pamong`),
  CONSTRAINT `desa_pamong_fk` FOREIGN KEY (`id_desa_pamong`) REFERENCES `tweb_desa_pamong` (`pamong_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `disposisi_surat_masuk_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_surat_fk` FOREIGN KEY (`id_surat_masuk`) REFERENCES `surat_masuk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dokumen` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `satuan` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `enabled` int NOT NULL DEFAULT '1',
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_pend` int DEFAULT NULL,
  `kategori` tinyint NOT NULL DEFAULT '1',
  `attr` text COLLATE utf8mb4_general_ci NOT NULL,
  `tipe` tinyint DEFAULT '1',
  `url` text COLLATE utf8mb4_general_ci,
  `tahun` int DEFAULT NULL,
  `kategori_info_publik` tinyint DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `id_syarat` int DEFAULT NULL,
  `id_parent` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_by` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dok_warga` tinyint(1) DEFAULT '0',
  `lokasi_arsip` varchar(150) COLLATE utf8mb4_general_ci DEFAULT '',
  `retensi_number` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `retensi_unit` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `retensi_date` timestamp NULL DEFAULT NULL,
  `published_at` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `status` enum('1','0') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `dokumen_config_fk` (`config_id`),
  KEY `id_pend_dokumen_fk` (`id_pend`),
  CONSTRAINT `dokumen_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_pend_dokumen_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `dokumen_hidup` AS SELECT 
 1 AS `id`,
 1 AS `config_id`,
 1 AS `satuan`,
 1 AS `nama`,
 1 AS `enabled`,
 1 AS `tgl_upload`,
 1 AS `id_pend`,
 1 AS `kategori`,
 1 AS `attr`,
 1 AS `tipe`,
 1 AS `url`,
 1 AS `tahun`,
 1 AS `kategori_info_publik`,
 1 AS `updated_at`,
 1 AS `deleted`,
 1 AS `id_syarat`,
 1 AS `id_parent`,
 1 AS `created_at`,
 1 AS `created_by`,
 1 AS `updated_by`,
 1 AS `dok_warga`,
 1 AS `lokasi_arsip`,
 1 AS `keterangan`,
 1 AS `status`,
 1 AS `retensi_date`,
 1 AS `retensi_number`,
 1 AS `retensi_unit`,
 1 AS `published_at`*/;
SET character_set_client = @saved_cs_client;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dtks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `is_draft` tinyint(1) NOT NULL DEFAULT '1',
  `id_rtm` int DEFAULT NULL,
  `id_keluarga` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `versi_kuisioner` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `kode_provinsi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_kabupaten` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_kecamatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_desa` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_sls_non_sls` varchar(4) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_sub_sls` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_sls_non_sls` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_urut_bangunan_tinggal` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_urut_keluarga_verif` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_keluarga` varchar(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_landmark_wilkerstat` varchar(6) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_kk` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_urut_ruta` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_pencacahan` date DEFAULT NULL,
  `nama_petugas_pencacahan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_petugas_pencacahan` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_pemeriksaan` date DEFAULT NULL,
  `nama_pemeriksa` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_pemeriksa` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_responden` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_hasil_pencacahan_ruta` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_pendataan` date DEFAULT NULL,
  `nama_ppl` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_ppl` varchar(4) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_pml` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_pml` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_hasil_pendataan_keluarga` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_hp_responden` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_stat_bangunan_tinggal` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_stat_lahan_tinggal` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sertiv_lahan_milik` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `luas_lantai` int DEFAULT NULL,
  `kd_jenis_lantai_terluas` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_jenis_dinding` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_kondisi_dinding` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_jenis_atap` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_kondisi_atap` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_kamar_tidur` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sumber_air_minum` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_jarak_sumber_air_ke_tpl` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_memperoleh_air_minum` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sumber_penerangan_utama` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_daya_terpasang` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_daya_terpasang2` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_daya_terpasang3` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_pelanggan_daya` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_bahan_bakar_memasak` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_fasilitas_tempat_bab` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_jenis_kloset` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_pembuangan_akhir_tinja` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_tabung_gas_3_kg` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_tabung_gas_5_5_kg` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_tabung_gas_12_kg` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_lemari_es` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_ac` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_pemanas_air` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_telepon_rumah` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_televisi` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_perhiasan_10_gr_emas` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_rek_aktif` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_komputer_laptop` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sepeda_motor` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_mobil` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_perahu` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_kapal_perahu_motor` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_featured_phone` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_smartphone` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sepeda` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_lahan` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `luas_lahan` int DEFAULT NULL,
  `kd_ada_sertiv_lahan` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_rumah_ditempat_lain` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_sapi` int DEFAULT NULL,
  `jumlah_kerbau` int DEFAULT NULL,
  `jumlah_kuda` int DEFAULT NULL,
  `jumlah_babi` int DEFAULT NULL,
  `jumlah_kambing_domba` int DEFAULT NULL,
  `jumlah_unggas` int DEFAULT NULL,
  `jumlah_ikan` int DEFAULT NULL,
  `jumlah_lainnya` int DEFAULT NULL,
  `kd_ada_art_usaha_sendiri_bersama` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_internet_sebulan` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_pengeluaran_pulsa_dan_data` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_ada_art_lanjut_usia` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_bss_bnpt` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_bss_bnpt` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_bss_bnpt` year DEFAULT NULL,
  `kd_pkh` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_pkh` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_pkh` year DEFAULT NULL,
  `kd_bst_covid19` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_bst_covid19` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_bst_covid19` year DEFAULT NULL,
  `kd_blt_dana_desa` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_blt_dana_desa` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_blt_dana_desa` year DEFAULT NULL,
  `kd_subsidi_listrik` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_subsidi_listrik` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_subsidi_listrik` year DEFAULT NULL,
  `kd_asuransi_lain` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_asuransi_lain` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_asuransi_lain` year DEFAULT NULL,
  `kd_bantuan_pemprov` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_bantuan_pemprov` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_bantuan_pemprov` year DEFAULT NULL,
  `kd_bantuan_pemkabkot` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_bantuan_pemkabkot` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_bantuan_pemkabkot` year DEFAULT NULL,
  `kd_bantuan_pemdes` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_bantuan_pemdes` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_bantuan_pemdes` year DEFAULT NULL,
  `kd_bantuan_pemda` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_bantuan_pemda` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_bantuan_pemda` year DEFAULT NULL,
  `kd_bantuan_masyarakat` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_bantuan_masyarakat` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_bantuan_masyarakat` year DEFAULT NULL,
  `kd_subsidi_pupuk` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_subsidi_pupuk` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_subsidi_pupuk` year DEFAULT NULL,
  `kd_subsidi_lpg` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_subsidi_lpg` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_subsidi_lpg` year DEFAULT NULL,
  `kd_konsumsi_daging` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_makan` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_beli_pakaian_baru` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_bayar_biaya_pengobatan` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_bahasa_wawancara` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tulis_bahasa_daerah` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dtks_config_fk` (`config_id`),
  KEY `fk_dtks_rtm` (`id_rtm`),
  KEY `fk_kel_dtks` (`id_keluarga`),
  CONSTRAINT `dtks_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_dtks_rtm` FOREIGN KEY (`id_rtm`) REFERENCES `tweb_rtm` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_kel_dtks` FOREIGN KEY (`id_keluarga`) REFERENCES `tweb_keluarga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dtks_anggota` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_dtks` int DEFAULT NULL,
  `id_penduduk` int DEFAULT NULL,
  `id_keluarga` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `kd_ket_keberadaan_art` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_meninggal` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_meninggal` year DEFAULT NULL,
  `kd_punya_akta_meniggal` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_pindah_tempat` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_pindah_tempat` year DEFAULT NULL,
  `kd_tempat_tinggal_saat_ini` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bulan_masuk_ruta` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_masuk_ruta` year DEFAULT NULL,
  `kd_alasan_masuk_ruta` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_hubungan_dg_krt` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_hubungan_dg_kk` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_jenis_kelamin` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_punya_aktanikah_cerai` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_punya_kartuid` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sulit_penglihatan` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sulit_pendengaran` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sulit_jalan_naiktangga` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sulit_gerak_tangan_jari` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sulit_belajar_intelektual` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sulit_ingat_konsentrasi` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sulit_perilaku_emosi` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sulit_paham_bicara_kom` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sulit_mandiri` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_sering_sedih_depresi` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_memiliki_perawat` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_merokok_sebulan_akhir` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_penyakit_kronis_menahun` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_partisipasi_sekolah` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_pendidikan_tertinggi` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_kelas_tertinggi` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_ijazah_tertinggi` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_bekerja_seminggu_lalu` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_jam_kerja_seminggu_lalu` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pendapatan_sebulan_terakhir` bigint DEFAULT NULL,
  `kd_punya_npwp` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `npwp` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_lapangan_usaha_pekerjaan` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_kedudukan_di_pekerjaan` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_gizi_seimbang` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_imunasasi_lengkap` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_bantuan_pempus` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_bantuan_pemkot` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_bantuan_pemdes` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_jamkes_setahun` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_ikut_pbijkn_bpjssehat` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_ikut_bpjssehat_nonpbi` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_ikut_jamsostek_bpjsk` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_ikut_pip` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_ikut_prakerja` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_ikut_kur` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_ikut_umi` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_jamket_kerja` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_usaha_sendiri_bersama` tinyint(1) NOT NULL DEFAULT '0',
  `kd_punya_usaha_sendiri_bersama` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_usaha_sendiri_bersama` tinyint DEFAULT NULL,
  `kd_lapangan_usaha_dr_usaha` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tulis_lapangan_usaha_dr_usaha` varchar(191) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `tulis_lapangan_usaha_pekerjaan` varchar(191) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `jumlah_pekerja_dibayar` tinyint DEFAULT NULL,
  `jumlah_pekerja_tidak_dibayar` tinyint DEFAULT NULL,
  `kd_kepemilikan_ijin_usaha` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_omset_usaha_perbulan` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kd_guna_internet_usaha` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dtks_anggota_config_fk` (`config_id`),
  KEY `fk_dtks_dtks_anggota` (`id_dtks`),
  KEY `fk_pend_dtks_anggota` (`id_penduduk`),
  KEY `fk_kel_dtks_anggota` (`id_keluarga`),
  CONSTRAINT `dtks_anggota_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_dtks_dtks_anggota` FOREIGN KEY (`id_dtks`) REFERENCES `dtks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_kel_dtks_anggota` FOREIGN KEY (`id_keluarga`) REFERENCES `tweb_keluarga` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_pend_dtks_anggota` FOREIGN KEY (`id_penduduk`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dtks_lampiran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_rtm` int DEFAULT NULL,
  `judul` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `dtks_lampiran_config_fk` (`config_id`),
  KEY `fk_dtks_lampiran_rtm` (`id_rtm`),
  CONSTRAINT `dtks_lampiran_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_dtks_lampiran_rtm` FOREIGN KEY (`id_rtm`) REFERENCES `tweb_rtm` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dtks_pengaturan_program` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `versi_kuisioner` int NOT NULL,
  `kode` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `id_bantuan` int DEFAULT NULL,
  `nilai_default` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `target_table` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `target_field` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_idversi_kuisionerkode` (`config_id`,`versi_kuisioner`,`kode`),
  KEY `fk_dtks_p_program` (`id_bantuan`),
  CONSTRAINT `dtks_pengaturan_program_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_dtks_p_program` FOREIGN KEY (`id_bantuan`) REFERENCES `program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dtks_ref_lampiran` (
  `id_dtks` int NOT NULL,
  `id_lampiran` int NOT NULL,
  `config_id` int DEFAULT NULL,
  KEY `fk_ref_lampiran_dtks` (`id_dtks`),
  KEY `fk_lampiran_dtks` (`id_lampiran`),
  KEY `dtks_ref_lampiran_config_id_foreign` (`config_id`),
  CONSTRAINT `dtks_ref_lampiran_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_lampiran_dtks` FOREIGN KEY (`id_lampiran`) REFERENCES `dtks_lampiran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_ref_lampiran_dtks` FOREIGN KEY (`id_dtks`) REFERENCES `dtks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fcm_token` (
  `id_user` int DEFAULT NULL,
  `config_id` int NOT NULL,
  `device` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `fcm_token_device_unique` (`device`),
  KEY `fcm_token_dd_user_fk` (`id_user`),
  KEY `fcm_token_config_fk` (`config_id`),
  CONSTRAINT `fcm_token_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fcm_token_dd_user_fk` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fcm_token_mandiri` (
  `id_user_mandiri` int DEFAULT NULL,
  `config_id` int NOT NULL,
  `device` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'id device dari android pemohon',
  `token` longtext COLLATE utf8mb4_general_ci NOT NULL COMMENT 'token yang didapat dari FCM',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `fcm_token_mandiri_device_unique` (`device`),
  KEY `fcm_token_mandiri_user_mandiri_fk` (`id_user_mandiri`),
  KEY `fcm_token_mandiri_config_fk` (`config_id`),
  CONSTRAINT `fcm_token_mandiri_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fcm_token_mandiri_user_mandiri_fk` FOREIGN KEY (`id_user_mandiri`) REFERENCES `tweb_penduduk_mandiri` (`id_pend`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gambar_gallery` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `parrent` int DEFAULT '0',
  `gambar` text COLLATE utf8mb4_general_ci,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `enabled` int NOT NULL DEFAULT '1',
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipe` int DEFAULT '0',
  `slider` tinyint(1) DEFAULT NULL,
  `urut` int DEFAULT NULL,
  `jenis` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `gambar_gallery_config_fk` (`config_id`),
  KEY `parrent` (`parrent`),
  CONSTRAINT `gambar_gallery_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `garis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `path` text COLLATE utf8mb4_general_ci,
  `enabled` int NOT NULL DEFAULT '1',
  `ref_line` int NOT NULL,
  `foto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desk` text COLLATE utf8mb4_general_ci,
  `id_cluster` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `garis_config_fk` (`config_id`),
  KEY `garis_cluster_fk` (`id_cluster`),
  CONSTRAINT `garis_cluster_fk` FOREIGN KEY (`id_cluster`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `garis_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gis_simbol` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `simbol` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `simbol_config` (`config_id`,`simbol`),
  CONSTRAINT `gis_simbol_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=639 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grup_akses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_grup` int NOT NULL,
  `id_modul` int NOT NULL,
  `akses` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_idid_grupid_modul` (`config_id`,`id_grup`,`id_modul`),
  KEY `grup_akses_config_fk` (`config_id`),
  KEY `id_grup` (`id_grup`),
  KEY `id_modul` (`id_modul`),
  CONSTRAINT `fk_id_grup` FOREIGN KEY (`id_grup`) REFERENCES `user_grup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_id_modul` FOREIGN KEY (`id_modul`) REFERENCES `setting_modul` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grup_akses_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=726 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hubung_warga` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_grup` int NOT NULL,
  `subjek` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hubung_warga_config_fk` (`config_id`),
  KEY `hubung_warga_id_grup_fk` (`id_grup`),
  CONSTRAINT `hubung_warga_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hubung_warga_id_grup_fk` FOREIGN KEY (`id_grup`) REFERENCES `kontak_grup` (`id_grup`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ibu_hamil` (
  `id_ibu_hamil` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `posyandu_id` int DEFAULT NULL,
  `kia_id` int DEFAULT NULL,
  `status_kehamilan` tinyint(1) DEFAULT NULL,
  `usia_kehamilan` tinyint DEFAULT NULL,
  `tanggal_melahirkan` date DEFAULT NULL,
  `pemeriksaan_kehamilan` tinyint(1) NOT NULL,
  `konsumsi_pil_fe` tinyint(1) NOT NULL,
  `butir_pil_fe` int NOT NULL,
  `pemeriksaan_nifas` tinyint(1) NOT NULL,
  `konseling_gizi` tinyint(1) NOT NULL,
  `kunjungan_rumah` tinyint(1) NOT NULL,
  `akses_air_bersih` tinyint(1) NOT NULL,
  `kepemilikan_jamban` tinyint(1) NOT NULL,
  `jaminan_kesehatan` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id_ibu_hamil`),
  KEY `ibu_hamil_config_fk` (`config_id`),
  KEY `ibu_hamil_posyandu_fk` (`posyandu_id`),
  KEY `ibu_hamil_kia_fk` (`kia_id`),
  CONSTRAINT `ibu_hamil_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ibu_hamil_kia_fk` FOREIGN KEY (`kia_id`) REFERENCES `kia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ibu_hamil_posyandu_fk` FOREIGN KEY (`posyandu_id`) REFERENCES `posyandu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ReceivingDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Text` text COLLATE utf8mb4_general_ci NOT NULL,
  `SenderNumber` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text COLLATE utf8mb4_general_ci NOT NULL,
  `SMSCNumber` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `Class` int NOT NULL DEFAULT '-1',
  `TextDecoded` text COLLATE utf8mb4_general_ci NOT NULL,
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `RecipientID` text COLLATE utf8mb4_general_ci NOT NULL,
  `Processed` enum('false','true') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`ID`),
  KEY `inbox_config_fk` (`config_id`),
  CONSTRAINT `inbox_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaris_asset` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_barang` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `register` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `judul_buku` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `spesifikasi_buku` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `asal_daerah` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pencipta` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bahan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_hewan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ukuran_hewan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_tumbuhan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ukuran_tumbuhan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` int NOT NULL,
  `tahun_pengadaan` year NOT NULL,
  `asal` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `visible` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventaris_asset_config_fk` (`config_id`),
  CONSTRAINT `inventaris_asset_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaris_gedung` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_barang` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `register` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `kondisi_bangunan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kontruksi_bertingkat` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kontruksi_beton` tinyint(1) DEFAULT '0',
  `luas_bangunan` int NOT NULL,
  `letak` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_dokument` date DEFAULT NULL,
  `no_dokument` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `luas` int DEFAULT NULL,
  `status_tanah` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_tanah` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `asal` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` double DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `visible` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventaris_gedung_config_fk` (`config_id`),
  CONSTRAINT `inventaris_gedung_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaris_jalan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_barang` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `register` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `kontruksi` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `panjang` int NOT NULL,
  `lebar` int NOT NULL,
  `luas` int NOT NULL,
  `letak` text COLLATE utf8mb4_general_ci,
  `tanggal_dokument` date NOT NULL,
  `no_dokument` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_tanah` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_tanah` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kondisi` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `asal` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `visible` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventaris_jalan_config_fk` (`config_id`),
  CONSTRAINT `inventaris_jalan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaris_kontruksi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kondisi_bangunan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kontruksi_bertingkat` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kontruksi_beton` tinyint(1) DEFAULT '0',
  `luas_bangunan` int NOT NULL,
  `letak` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_dokument` date DEFAULT NULL,
  `no_dokument` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status_tanah` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_tanah` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `asal` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `visible` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventaris_kontruksi_config_fk` (`config_id`),
  CONSTRAINT `inventaris_kontruksi_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaris_peralatan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_barang` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `register` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `merk` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ukuran` text COLLATE utf8mb4_general_ci,
  `bahan` text COLLATE utf8mb4_general_ci,
  `tahun_pengadaan` year NOT NULL,
  `no_pabrik` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_rangka` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_mesin` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_polisi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_bpkb` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `asal` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `visible` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventaris_peralatan_config_fk` (`config_id`),
  CONSTRAINT `inventaris_peralatan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaris_tanah` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_barang` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `register` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `luas` int NOT NULL,
  `tahun_pengadaan` year NOT NULL,
  `letak` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `hak` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `no_sertifikat` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_sertifikat` date DEFAULT NULL,
  `penggunaan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `asal` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` double NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `visible` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventaris_tanah_config_fk` (`config_id`),
  CONSTRAINT `inventaris_tanah_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kader_pemberdayaan_masyarakat` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `penduduk_id` int DEFAULT NULL,
  `kursus` text COLLATE utf8mb4_general_ci,
  `bidang` text COLLATE utf8mb4_general_ci,
  `keterangan` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  KEY `kader_pemberdayaan_masyarakat_config_fk` (`config_id`),
  KEY `kader_pemberdayaan_masyarakat_penduduk_fk` (`penduduk_id`),
  CONSTRAINT `kader_pemberdayaan_masyarakat_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kader_pemberdayaan_masyarakat_penduduk_fk` FOREIGN KEY (`penduduk_id`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tipe` int NOT NULL DEFAULT '1',
  `urut` tinyint NOT NULL,
  `enabled` tinyint NOT NULL,
  `parrent` int NOT NULL DEFAULT '0',
  `slug` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategori_config_fk` (`config_id`),
  CONSTRAINT `kategori_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kehadiran_alasan_keluar` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `alasan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kehadiran_alasan_keluar_config_fk` (`config_id`),
  CONSTRAINT `kehadiran_alasan_keluar_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kehadiran_hari_libur` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tanggal_config` (`config_id`,`tanggal`),
  CONSTRAINT `kehadiran_hari_libur_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kehadiran_jam_kerja` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama_hari` varchar(65) COLLATE utf8mb4_general_ci NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_keluar` time NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `keterangan` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jam_kerja_config` (`config_id`,`nama_hari`),
  CONSTRAINT `kehadiran_jam_kerja_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kehadiran_pengaduan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `waktu` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `id_penduduk` int DEFAULT NULL,
  `id_pamong` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kehadiran_pengaduan_config_fk` (`config_id`),
  KEY `kehadiran_pengaduan_penduduk_fk` (`id_penduduk`),
  KEY `kehadiran_pengaduan_pamong_fk` (`id_pamong`),
  CONSTRAINT `kehadiran_pengaduan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kehadiran_pengaduan_pamong_fk` FOREIGN KEY (`id_pamong`) REFERENCES `tweb_desa_pamong` (`pamong_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kehadiran_pengaduan_penduduk_fk` FOREIGN KEY (`id_penduduk`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kehadiran_pengajuan_izin` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `id_pamong` int NOT NULL,
  `jenis_izin` enum('izin','sakit','dinas_luar_kota','cuti','lainnya') COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Jenis izin yang diajukan',
  `tanggal_mulai` date NOT NULL COMMENT 'Tanggal mulai izin',
  `tanggal_selesai` date NOT NULL COMMENT 'Tanggal selesai izin',
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Keterangan alasan izin',
  `status_approval` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending' COMMENT 'Status persetujuan',
  `approved_by` int DEFAULT NULL,
  `tanggal_approval` datetime DEFAULT NULL COMMENT 'Tanggal approval/reject',
  `keterangan_approval` text COLLATE utf8mb4_general_ci COMMENT 'Keterangan dari atasan',
  `lampiran` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'File lampiran (untuk sakit, dll)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengajuan_izin_config_status_idx` (`config_id`,`status_approval`),
  KEY `pengajuan_izin_config_tanggal_idx` (`config_id`,`tanggal_mulai`),
  KEY `pengajuan_izin_pamong_status_idx` (`id_pamong`,`status_approval`),
  KEY `pengajuan_izin_approved_by_fk` (`approved_by`),
  CONSTRAINT `kehadiran_pengajuan_izin_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pengajuan_izin_approved_by_fk` FOREIGN KEY (`approved_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `pengajuan_izin_pamong_pamong_fk` FOREIGN KEY (`id_pamong`) REFERENCES `tweb_desa_pamong` (`pamong_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kehadiran_pengajuan_izin_detail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `pengajuan_izin_id` bigint unsigned NOT NULL COMMENT 'FK ke tabel pengajuan izin',
  `tanggal` date NOT NULL COMMENT 'Tanggal izin spesifik',
  `jenis_izin` enum('izin','sakit','dinas_luar_kota','cuti','lainnya') COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Jenis izin (copy dari header)',
  `id_pamong` int NOT NULL COMMENT 'ID pamong (copy dari header)',
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending' COMMENT 'Status approval (copy dari header)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengajuan_detail_config_tanggal_idx` (`config_id`,`tanggal`),
  KEY `pengajuan_detail_config_tanggal_status_idx` (`config_id`,`tanggal`,`status`),
  KEY `pengajuan_detail_pamong_tanggal_idx` (`config_id`,`id_pamong`,`tanggal`),
  KEY `pengajuan_detail_tanggal_jenis_idx` (`tanggal`,`jenis_izin`),
  KEY `pengajuan_detail_header_fk` (`pengajuan_izin_id`),
  KEY `pengajuan_detail_pamong_fk` (`id_pamong`),
  CONSTRAINT `kehadiran_pengajuan_izin_detail_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pengajuan_detail_header_fk` FOREIGN KEY (`pengajuan_izin_id`) REFERENCES `kehadiran_pengajuan_izin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pengajuan_detail_pamong_fk` FOREIGN KEY (`id_pamong`) REFERENCES `tweb_desa_pamong` (`pamong_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kehadiran_perangkat_desa` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `tanggal` date DEFAULT NULL,
  `pamong_id` int DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `status_kehadiran` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kehadiran_perangkat_desa_config_fk` (`config_id`),
  KEY `kehadiran_perangkat_desa_pamong_fk` (`pamong_id`),
  CONSTRAINT `kehadiran_perangkat_desa_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kehadiran_perangkat_desa_pamong_fk` FOREIGN KEY (`pamong_id`) REFERENCES `tweb_desa_pamong` (`pamong_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelompok` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_master` int NOT NULL,
  `id_ketua` int DEFAULT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_sk_pendirian` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'kelompok',
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_kode_tipe` (`config_id`,`kode`,`tipe`),
  UNIQUE KEY `slug_config_tipe` (`slug`,`config_id`),
  KEY `id_master` (`id_master`),
  KEY `id_ketua` (`id_ketua`),
  CONSTRAINT `kelompok_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kelompok_kelompok_master_fk` FOREIGN KEY (`id_master`) REFERENCES `kelompok_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kelompok_ketua_fk` FOREIGN KEY (`id_ketua`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelompok_anggota` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_kelompok` int NOT NULL,
  `id_penduduk` int NOT NULL,
  `no_anggota` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `jabatan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '90',
  `no_sk_jabatan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'kelompok',
  `periode` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nmr_sk_pengangkatan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tgl_sk_pengangkatan` date DEFAULT NULL,
  `nmr_sk_pemberhentian` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tgl_sk_pemberhentian` date DEFAULT NULL,
  `foto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_kelompok_config` (`config_id`,`id_kelompok`,`id_penduduk`),
  UNIQUE KEY `no_anggota_config` (`config_id`,`id_kelompok`,`no_anggota`),
  KEY `kelompok_anggota_kelompok_fk` (`id_kelompok`),
  KEY `kelompok_anggota_penduduk_fk` (`id_penduduk`),
  CONSTRAINT `kelompok_anggota_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kelompok_anggota_kelompok_fk` FOREIGN KEY (`id_kelompok`) REFERENCES `kelompok` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kelompok_anggota_penduduk_fk` FOREIGN KEY (`id_penduduk`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelompok_master` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `kelompok` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `tipe` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'kelompok',
  PRIMARY KEY (`id`),
  KEY `kelompok_master_config_fk` (`config_id`),
  CONSTRAINT `kelompok_master_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `keluarga_aktif` AS SELECT 
 1 AS `id`,
 1 AS `config_id`,
 1 AS `no_kk`,
 1 AS `nik_kepala`,
 1 AS `tgl_daftar`,
 1 AS `kelas_sosial`,
 1 AS `tgl_cetak_kk`,
 1 AS `alamat`,
 1 AS `id_cluster`,
 1 AS `updated_at`,
 1 AS `updated_by`*/;
SET character_set_client = @saved_cs_client;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keuangan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `template_uuid` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `anggaran` decimal(65,2) NOT NULL DEFAULT '0.00',
  `realisasi` decimal(65,2) NOT NULL DEFAULT '0.00',
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keuangan_config_id_foreign` (`config_id`),
  KEY `keuangan_template_uuid_foreign` (`template_uuid`),
  CONSTRAINT `keuangan_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `keuangan_template_uuid_foreign` FOREIGN KEY (`template_uuid`) REFERENCES `keuangan_template` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keuangan_manual_ref_rek1` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Akun` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Nama_Akun` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keuangan_manual_ref_rek2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Akun` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Kelompok` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Nama_Kelompok` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keuangan_manual_ref_rek3` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Kelompok` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Jenis` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Nama_Jenis` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keuangan_template` (
  `uuid` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `parent_uuid` char(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uraian` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uuid`),
  KEY `keuangan_template_parent_uuid_index` (`parent_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `no_kia` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ibu_id` int DEFAULT NULL,
  `anak_id` int DEFAULT NULL,
  `hari_perkiraan_lahir` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kia_config_fk` (`config_id`),
  KEY `no_kia` (`no_kia`),
  KEY `kia_ibu_fk` (`ibu_id`),
  KEY `kia_anak_fk` (`anak_id`),
  CONSTRAINT `kia_anak_fk` FOREIGN KEY (`anak_id`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kia_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kia_ibu_fk` FOREIGN KEY (`ibu_id`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `klasifikasi_surat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `kode` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` text COLLATE utf8mb4_general_ci NOT NULL,
  `uraian` mediumtext COLLATE utf8mb4_general_ci NOT NULL,
  `enabled` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_idkode` (`config_id`,`kode`),
  KEY `klasifikasi_surat_config_fk` (`config_id`),
  CONSTRAINT `klasifikasi_surat_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=994 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `komentar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_artikel` int DEFAULT NULL,
  `owner` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subjek` tinytext COLLATE utf8mb4_general_ci,
  `komentar` text COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT NULL,
  `tipe` tinyint(1) DEFAULT NULL,
  `no_hp` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_archived` tinyint(1) DEFAULT '0',
  `permohonan` text COLLATE utf8mb4_general_ci,
  `parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `komentar_config_fk` (`config_id`),
  KEY `komentar_artikel_fk` (`id_artikel`),
  CONSTRAINT `komentar_artikel_fk` FOREIGN KEY (`id_artikel`) REFERENCES `artikel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `komentar_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kontak` (
  `id_kontak` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telegram` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hubung_warga` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Telegram',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kontak`),
  UNIQUE KEY `email_config` (`config_id`,`email`),
  UNIQUE KEY `telegram_config` (`config_id`,`telegram`),
  UNIQUE KEY `telepon_config` (`config_id`,`telepon`),
  CONSTRAINT `kontak_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kontak_grup` (
  `id_grup` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama_grup` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_grup`),
  KEY `kontak_grup_config_fk` (`config_id`),
  CONSTRAINT `kontak_grup_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lampiran_surat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` tinyint NOT NULL DEFAULT '2',
  `template` longtext COLLATE utf8mb4_general_ci,
  `template_desa` longtext COLLATE utf8mb4_general_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_config` (`config_id`,`slug`),
  CONSTRAINT `lampiran_surat_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `laporan_sinkronisasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `tipe` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `judul` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun` int NOT NULL,
  `semester` int NOT NULL,
  `nama_file` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kirim` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `laporan_sinkronisasi_config_fk` (`config_id`),
  CONSTRAINT `laporan_sinkronisasi_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `line` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `simbol` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe` int DEFAULT '0',
  `tebal` int DEFAULT '3',
  `jenis` varchar(10) COLLATE utf8mb4_general_ci DEFAULT 'solid',
  `parrent` int DEFAULT '1',
  `enabled` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `line_config_fk` (`config_id`),
  KEY `parrent` (`parrent`),
  CONSTRAINT `line_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `log_activity_config_id_foreign` (`config_id`),
  KEY `log_activity_log_name_index` (`log_name`),
  CONSTRAINT `log_activity_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_backup` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `ukuran` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `path` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `permanen` tinyint(1) NOT NULL DEFAULT '0',
  `downloaded_at` timestamp NULL DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `pid_process` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `log_backup_config_fk` (`config_id`),
  CONSTRAINT `log_backup_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_ekspor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tgl_ekspor` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `kode_ekspor` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `semua` int NOT NULL DEFAULT '1',
  `dari_tgl` date DEFAULT NULL,
  `total` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_hapus_penduduk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_pend` int DEFAULT NULL,
  `nik` decimal(16,0) NOT NULL,
  `foto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_by` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `log_hapus_penduduk_config_fk` (`config_id`),
  KEY `log_hapus_penduduk_pend_fk` (`id_pend`),
  CONSTRAINT `log_hapus_penduduk_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_hapus_penduduk_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_keluarga` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_kk` int DEFAULT NULL,
  `id_peristiwa` int NOT NULL,
  `tgl_peristiwa` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_pend` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `id_log_penduduk` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_kk_config` (`config_id`,`id_kk`,`id_peristiwa`,`tgl_peristiwa`,`id_pend`),
  KEY `log_keluarga_kk_fk` (`id_kk`),
  KEY `log_keluarga_pend_fk` (`id_pend`),
  KEY `log_penduduk_fk` (`id_log_penduduk`),
  CONSTRAINT `log_keluarga_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_keluarga_kk_fk` FOREIGN KEY (`id_kk`) REFERENCES `tweb_keluarga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_keluarga_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_penduduk_fk` FOREIGN KEY (`id_log_penduduk`) REFERENCES `log_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_notifikasi_admin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `config_id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text COLLATE utf8mb4_general_ci NOT NULL,
  `token` longtext COLLATE utf8mb4_general_ci,
  `device` longtext COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payload` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `read` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_notifikasi_admin_id_created_at_read_device_config_id_index` (`id`,`created_at`,`read`,`config_id`),
  KEY `log_notifikasi_admin_user_fk` (`id_user`),
  KEY `log_notifikasi_admin_config_fk` (`config_id`),
  CONSTRAINT `log_notifikasi_admin_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_notifikasi_admin_user_fk` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_notifikasi_mandiri` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user_mandiri` int DEFAULT NULL,
  `config_id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Judul notifikasi',
  `isi` text COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Isi notifikasi',
  `token` longtext COLLATE utf8mb4_general_ci,
  `device` longtext COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'gambar notifikasi, jika ada',
  `payload` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tujuan navicasi saat notifikasi di klik',
  `read` tinyint NOT NULL COMMENT 'menandatakan notifikasi sudah terbaca atau belum, 1 artinya sudah dibaca, 0 artinya belum dibaca',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_notifikasi_mandiri_id_created_at_read_device_config_id_index` (`id`,`created_at`,`read`,`config_id`),
  KEY `log_notifikasi_mandiri_user_mandiri_fk` (`id_user_mandiri`),
  KEY `log_notifikasi_mandiri_config_fk` (`config_id`),
  CONSTRAINT `log_notifikasi_mandiri_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_notifikasi_mandiri_user_mandiri_fk` FOREIGN KEY (`id_user_mandiri`) REFERENCES `tweb_penduduk_mandiri` (`id_pend`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_penduduk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_pend` int NOT NULL,
  `kode_peristiwa` int DEFAULT NULL,
  `meninggal_di` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jam_mati` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sebab` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `penolong_mati` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `akta_mati` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_akta_mati` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_tujuan` tinytext COLLATE utf8mb4_general_ci,
  `tgl_lapor` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tgl_peristiwa` datetime DEFAULT CURRENT_TIMESTAMP,
  `catatan` text COLLATE utf8mb4_general_ci,
  `no_kk` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_kk` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ref_pindah` tinyint DEFAULT '1',
  `maksud_tujuan_kedatangan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_pend_config` (`config_id`,`id_pend`,`kode_peristiwa`,`tgl_peristiwa`),
  KEY `config_id` (`config_id`),
  KEY `id_pend` (`id_pend`),
  KEY `kode_peristiwa` (`kode_peristiwa`),
  KEY `tgl_peristiwa` (`tgl_peristiwa`),
  KEY `id_ref_pindah` (`ref_pindah`),
  CONSTRAINT `fk_tweb_penduduk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_ref_pindah` FOREIGN KEY (`ref_pindah`) REFERENCES `ref_pindah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_penduduk_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_perubahan_penduduk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_pend` int DEFAULT NULL,
  `id_cluster` int DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `log_perubahan_penduduk_config_fk` (`config_id`),
  KEY `log_perubahan_penduduk_pend_fk` (`id_pend`),
  KEY `log_perubahan_penduduk_cluster_fk` (`id_cluster`),
  CONSTRAINT `log_perubahan_penduduk_cluster_fk` FOREIGN KEY (`id_cluster`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_perubahan_penduduk_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_perubahan_penduduk_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_perubahan_surat` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `log_surat_id` int DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_perubahan_surat_config_id_foreign` (`config_id`),
  CONSTRAINT `log_perubahan_surat_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_restore_desa` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `ukuran` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `path` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `restore_at` timestamp NULL DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `pid_process` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_restore_desa_config_fk` (`config_id`),
  CONSTRAINT `log_restore_desa_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_sinkronisasi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `modul` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modul_config` (`config_id`,`modul`),
  CONSTRAINT `log_sinkronisasi_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_surat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_format_surat` int DEFAULT NULL,
  `id_pend` int DEFAULT NULL,
  `id_pamong` int DEFAULT NULL,
  `nama_pamong` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Nama pamong agar tidak berubah saat ada perubahan di master pamong',
  `nama_jabatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bulan` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun` varchar(4) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_surat` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_surat` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lampiran` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nik_non_warga` decimal(16,0) DEFAULT NULL,
  `nama_non_warga` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `lokasi_arsip` varchar(150) COLLATE utf8mb4_general_ci DEFAULT '',
  `urls_id` int DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0. Konsep, 1. Cetak',
  `log_verifikasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tte` tinyint(1) DEFAULT NULL,
  `verifikasi_operator` tinyint(1) DEFAULT NULL,
  `verifikasi_kades` tinyint(1) DEFAULT NULL,
  `verifikasi_sekdes` tinyint(1) DEFAULT NULL,
  `isi_surat` longtext COLLATE utf8mb4_general_ci,
  `isi_surat_temp` longtext COLLATE utf8mb4_general_ci,
  `kecamatan` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `pemohon` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `input` longtext COLLATE utf8mb4_general_ci,
  `lock` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `urls_id` (`urls_id`),
  KEY `log_surat_config_fk` (`config_id`),
  KEY `log_surat_format_surat_fk` (`id_format_surat`),
  KEY `log_surat_pend_fk` (`id_pend`),
  KEY `log_surat_pamong_fk` (`id_pamong`),
  KEY `log_surat_user_fk` (`id_user`),
  CONSTRAINT `log_surat_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_surat_format_surat_fk` FOREIGN KEY (`id_format_surat`) REFERENCES `tweb_surat_format` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_surat_pamong_fk` FOREIGN KEY (`id_pamong`) REFERENCES `tweb_desa_pamong` (`pamong_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_surat_pamong_urls_fk` FOREIGN KEY (`urls_id`) REFERENCES `urls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_surat_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_surat_user_fk` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_surat_dinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_format_surat` int NOT NULL,
  `id_pamong` int NOT NULL,
  `nama_pamong` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Nama pamong agar tidak berubah saat ada perubahan di master pamong',
  `nama_jabatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_user` int NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bulan` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun` varchar(4) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_surat` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_surat` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lampiran` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lokasi_arsip` varchar(150) COLLATE utf8mb4_general_ci DEFAULT '',
  `urls_id` int DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0. Konsep, 1. Cetak',
  `log_verifikasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tte` tinyint(1) DEFAULT NULL,
  `verifikasi_operator` tinyint(1) DEFAULT NULL,
  `verifikasi_kades` tinyint(1) DEFAULT NULL,
  `verifikasi_sekdes` tinyint(1) DEFAULT NULL,
  `isi_surat` longtext COLLATE utf8mb4_general_ci,
  `input` longtext COLLATE utf8mb4_general_ci,
  `karakter` tinyint DEFAULT '1' COMMENT '1:biasa, 2:terbatas, 3:rahasia',
  `derajat` tinyint DEFAULT '1' COMMENT '1:biasa, 2:segera, 3:sangat segera',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `urls_id` (`urls_id`),
  KEY `log_surat_config_fk` (`config_id`),
  KEY `log_surat_dinas_format_fk` (`id_format_surat`),
  KEY `log_surat_dinas_user_fk` (`id_user`),
  KEY `log_surat_dinas_created_by_fk` (`created_by`),
  KEY `log_surat_dinas_updated_by_fk` (`updated_by`),
  CONSTRAINT `log_surat_dinas_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_surat_dinas_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_surat_dinas_format_fk` FOREIGN KEY (`id_format_surat`) REFERENCES `surat_dinas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_surat_dinas_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_surat_dinas_user_fk` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_tolak` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_surat` int DEFAULT NULL,
  `keterangan` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  `id_surat_dinas` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_tolak_config_fk` (`config_id`),
  KEY `log_tolak_surat_fk` (`id_surat`),
  KEY `log_tolak_surat_dinas_fk` (`id_surat_dinas`),
  CONSTRAINT `log_tolak_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_tolak_surat_dinas_fk` FOREIGN KEY (`id_surat_dinas`) REFERENCES `log_surat_dinas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_tolak_surat_fk` FOREIGN KEY (`id_surat`) REFERENCES `log_surat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_tte` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `jenis_error` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_tte_config_fk` (`config_id`),
  CONSTRAINT `log_tte_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lokasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `desk` text COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `enabled` int NOT NULL DEFAULT '1',
  `lat` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lng` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ref_point` int DEFAULT NULL,
  `foto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_cluster` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lokasi_config_fk` (`config_id`),
  KEY `ref_point` (`ref_point`),
  KEY `lokasi_cluster_fk` (`id_cluster`),
  CONSTRAINT `lokasi_cluster_fk` FOREIGN KEY (`id_cluster`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lokasi_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lokasi_point_fk` FOREIGN KEY (`ref_point`) REFERENCES `point` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `master_inventaris` AS SELECT 
 1 AS `asset`,
 1 AS `config_id`,
 1 AS `id`,
 1 AS `nama_barang`,
 1 AS `kode_barang`,
 1 AS `kondisi`,
 1 AS `keterangan`,
 1 AS `asal`,
 1 AS `tahun_pengadaan`*/;
SET character_set_client = @saved_cs_client;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_sosial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `gambar` text COLLATE utf8mb4_general_ci NOT NULL,
  `link` text COLLATE utf8mb4_general_ci,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tipe` tinyint(1) DEFAULT '1',
  `enabled` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_sosial_config` (`config_id`,`nama`),
  CONSTRAINT `media_sosial_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `link` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `parrent` int DEFAULT '0',
  `link_tipe` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1',
  `urut` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_config_fk` (`config_id`),
  CONSTRAINT `menu_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `versi_database` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `premium` text COLLATE utf8mb4_general_ci,
  `config_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `versi_database_config` (`config_id`,`versi_database`),
  CONSTRAINT `migrasi_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mutasi_cdesa` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_cdesa_masuk` int unsigned DEFAULT NULL,
  `cdesa_keluar` int unsigned DEFAULT NULL,
  `jenis_mutasi` tinyint DEFAULT NULL,
  `tanggal_mutasi` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `id_persil` int NOT NULL,
  `no_bidang_persil` tinyint DEFAULT NULL,
  `luas` decimal(7,0) DEFAULT NULL,
  `no_objek_pajak` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `path` text COLLATE utf8mb4_general_ci,
  `id_peta` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mutasi_cdesa_config_fk` (`config_id`),
  KEY `cdesa_mutasi_fk` (`id_cdesa_masuk`),
  KEY `mutasi_cdesa_peta_fk` (`id_peta`),
  CONSTRAINT `cdesa_mutasi_fk` FOREIGN KEY (`id_cdesa_masuk`) REFERENCES `cdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mutasi_cdesa_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mutasi_cdesa_peta_fk` FOREIGN KEY (`id_peta`) REFERENCES `area` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mutasi_inventaris_asset` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_inventaris_asset` int DEFAULT NULL,
  `jenis_mutasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `sumbangkan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `visible` int NOT NULL DEFAULT '1',
  `status_mutasi` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mutasi_inventaris_asset_config_fk` (`config_id`),
  KEY `fk_mutasi_inventaris_asset` (`id_inventaris_asset`),
  CONSTRAINT `FK_mutasi_inventaris_asset` FOREIGN KEY (`id_inventaris_asset`) REFERENCES `inventaris_asset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mutasi_inventaris_asset_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mutasi_inventaris_gedung` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_inventaris_gedung` int DEFAULT NULL,
  `jenis_mutasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `sumbangkan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `visible` int NOT NULL DEFAULT '1',
  `status_mutasi` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mutasi_inventaris_gedung_config_fk` (`config_id`),
  KEY `fk_mutasi_inventaris_gedung` (`id_inventaris_gedung`),
  CONSTRAINT `FK_mutasi_inventaris_gedung` FOREIGN KEY (`id_inventaris_gedung`) REFERENCES `inventaris_gedung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mutasi_inventaris_gedung_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mutasi_inventaris_jalan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_inventaris_jalan` int DEFAULT NULL,
  `jenis_mutasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `sumbangkan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `visible` int NOT NULL DEFAULT '1',
  `status_mutasi` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mutasi_inventaris_jalan_config_fk` (`config_id`),
  KEY `fk_mutasi_inventaris_jalan` (`id_inventaris_jalan`),
  CONSTRAINT `FK_mutasi_inventaris_jalan` FOREIGN KEY (`id_inventaris_jalan`) REFERENCES `inventaris_jalan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mutasi_inventaris_jalan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mutasi_inventaris_peralatan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_inventaris_peralatan` int DEFAULT NULL,
  `jenis_mutasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `sumbangkan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `visible` int NOT NULL DEFAULT '1',
  `status_mutasi` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mutasi_inventaris_peralatan_config_fk` (`config_id`),
  KEY `fk_mutasi_inventaris_peralatan` (`id_inventaris_peralatan`),
  CONSTRAINT `FK_mutasi_inventaris_peralatan` FOREIGN KEY (`id_inventaris_peralatan`) REFERENCES `inventaris_peralatan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mutasi_inventaris_peralatan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mutasi_inventaris_tanah` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_inventaris_tanah` int DEFAULT NULL,
  `jenis_mutasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_mutasi` date NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `sumbangkan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `visible` int NOT NULL DEFAULT '1',
  `status_mutasi` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mutasi_inventaris_tanah_config_fk` (`config_id`),
  KEY `fk_mutasi_inventaris_tanah` (`id_inventaris_tanah`),
  CONSTRAINT `mutasi_inventaris_tanah_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mutasi_inventaris_tanah_inventaris_tanah_fk` FOREIGN KEY (`id_inventaris_tanah`) REFERENCES `inventaris_tanah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `config_id` int DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_general_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_config_id_foreign` (`config_id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  CONSTRAINT `notifications_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `kode` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `judul` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text COLLATE utf8mb4_general_ci NOT NULL,
  `server` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_berikutnya` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int NOT NULL,
  `frekuensi` smallint NOT NULL,
  `aksi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `aktif` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_config` (`config_id`,`kode`),
  CONSTRAINT `notifikasi_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `one_time_passwords` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `origin_properties` text COLLATE utf8mb4_general_ci,
  `expires_at` datetime NOT NULL,
  `authenticatable_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `authenticatable_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `one_time_passwords_authenticatable_type_authenticatable_id_index` (`authenticatable_type`,`authenticatable_id`),
  KEY `one_time_passwords_config_id_foreign` (`config_id`),
  CONSTRAINT `one_time_passwords_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `otp_token` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `token_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `channel` enum('email','telegram') COLLATE utf8mb4_general_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `purpose` enum('activation','login') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'login',
  `expires_at` timestamp NOT NULL,
  `attempts` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `otp_token_user_id_expires_at_index` (`user_id`,`expires_at`),
  KEY `otp_token_config_id_foreign` (`config_id`),
  CONSTRAINT `otp_token_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `otp_token_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `outbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `InsertIntoDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SendingDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SendBefore` time NOT NULL DEFAULT '23:59:59',
  `SendAfter` time NOT NULL DEFAULT '00:00:00',
  `Text` text COLLATE utf8mb4_general_ci,
  `DestinationNumber` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text COLLATE utf8mb4_general_ci,
  `Class` int DEFAULT '-1',
  `TextDecoded` text COLLATE utf8mb4_general_ci NOT NULL,
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `MultiPart` enum('false','true') COLLATE utf8mb4_general_ci DEFAULT 'false',
  `RelativeValidity` int DEFAULT '-1',
  `SenderID` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `SendingTimeOut` timestamp NULL DEFAULT NULL,
  `DeliveryReport` enum('default','yes','no') COLLATE utf8mb4_general_ci DEFAULT 'default',
  `CreatorID` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`ID`),
  KEY `outbox_date_config` (`SendingDateTime`,`SendingTimeOut`),
  KEY `outbox_sender_config` (`config_id`,`SenderID`),
  CONSTRAINT `outbox_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pelapak` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_pend` int DEFAULT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lat` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lng` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zoom` tinyint NOT NULL DEFAULT '10',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `pelapak_config_fk` (`config_id`),
  KEY `pelapak_pend_fk` (`id_pend`),
  CONSTRAINT `pelapak_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pelapak_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembangunan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_lokasi` int DEFAULT NULL,
  `sumber_dana` text COLLATE utf8mb4_general_ci,
  `judul` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `lokasi` text COLLATE utf8mb4_general_ci,
  `lat` varchar(225) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lng` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `volume` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_anggaran` year DEFAULT NULL,
  `pelaksana_kegiatan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `anggaran` bigint DEFAULT '0',
  `perubahan_anggaran` int DEFAULT '0',
  `sumber_biaya_pemerintah` bigint DEFAULT '0',
  `sumber_biaya_provinsi` bigint DEFAULT '0',
  `sumber_biaya_kab_kota` bigint DEFAULT '0',
  `sumber_biaya_swadaya` bigint DEFAULT '0',
  `sumber_biaya_jumlah` bigint DEFAULT '0',
  `manfaat` text COLLATE utf8mb4_general_ci,
  `waktu` int DEFAULT '0',
  `satuan_waktu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Hari, 2 = Minggu, 3 = Bulan, 4 = Tahun',
  `sifat_proyek` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'BARU',
  `realisasi_anggaran` bigint DEFAULT '0',
  `silpa` bigint DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_config` (`config_id`,`slug`),
  KEY `id_lokasi` (`id_lokasi`),
  CONSTRAINT `pembangunan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pembangunan_lokasi_cluster_fk` FOREIGN KEY (`id_lokasi`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembangunan_ref_dokumentasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_pembangunan` int DEFAULT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `persentase` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembangunan_ref_dokumentasi_config_fk` (`config_id`),
  KEY `id_pembangunan` (`id_pembangunan`),
  CONSTRAINT `pembangunan_ref_dokumentasi_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pembangunan_ref_dokumentasi_pembangunan_fk` FOREIGN KEY (`id_pembangunan`) REFERENCES `pembangunan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pemilihan` (
  `uuid` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `config_id` int NOT NULL,
  `judul` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`uuid`),
  UNIQUE KEY `pemilihan_uuid_config_id_unique` (`uuid`,`config_id`),
  KEY `pemilihan_config_id_foreign` (`config_id`),
  CONSTRAINT `pemilihan_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pendapat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `pengguna` text COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pilihan` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pendapat_config_fk` (`config_id`),
  CONSTRAINT `pendapat_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `penduduk_hidup` AS SELECT 
 1 AS `id`,
 1 AS `config_id`,
 1 AS `nama`,
 1 AS `nik`,
 1 AS `id_kk`,
 1 AS `kk_level`,
 1 AS `id_rtm`,
 1 AS `rtm_level`,
 1 AS `sex`,
 1 AS `tempatlahir`,
 1 AS `tanggallahir`,
 1 AS `agama_id`,
 1 AS `pendidikan_kk_id`,
 1 AS `pendidikan_sedang_id`,
 1 AS `pekerjaan_id`,
 1 AS `status_kawin`,
 1 AS `warganegara_id`,
 1 AS `dokumen_pasport`,
 1 AS `dokumen_kitas`,
 1 AS `ayah_nik`,
 1 AS `ibu_nik`,
 1 AS `nama_ayah`,
 1 AS `nama_ibu`,
 1 AS `foto`,
 1 AS `golongan_darah_id`,
 1 AS `id_cluster`,
 1 AS `status`,
 1 AS `alamat_sebelumnya`,
 1 AS `alamat_sekarang`,
 1 AS `status_dasar`,
 1 AS `hamil`,
 1 AS `cacat_id`,
 1 AS `sakit_menahun_id`,
 1 AS `akta_lahir`,
 1 AS `akta_perkawinan`,
 1 AS `tanggalperkawinan`,
 1 AS `akta_perceraian`,
 1 AS `tanggalperceraian`,
 1 AS `cara_kb_id`,
 1 AS `telepon`,
 1 AS `tanggal_akhir_paspor`,
 1 AS `no_kk_sebelumnya`,
 1 AS `ktp_el`,
 1 AS `status_rekam`,
 1 AS `waktu_lahir`,
 1 AS `tempat_dilahirkan`,
 1 AS `jenis_kelahiran`,
 1 AS `kelahiran_anak_ke`,
 1 AS `penolong_kelahiran`,
 1 AS `berat_lahir`,
 1 AS `panjang_lahir`,
 1 AS `tag_id_card`,
 1 AS `created_at`,
 1 AS `created_by`,
 1 AS `updated_at`,
 1 AS `updated_by`,
 1 AS `id_asuransi`,
 1 AS `no_asuransi`,
 1 AS `email`,
 1 AS `email_token`,
 1 AS `email_tgl_kadaluarsa`,
 1 AS `email_tgl_verifikasi`,
 1 AS `telegram`,
 1 AS `telegram_token`,
 1 AS `telegram_tgl_kadaluarsa`,
 1 AS `telegram_tgl_verifikasi`,
 1 AS `bahasa_id`,
 1 AS `ket`,
 1 AS `negara_asal`,
 1 AS `tempat_cetak_ktp`,
 1 AS `tanggal_cetak_ktp`,
 1 AS `pekerja_migran`,
 1 AS `suku`,
 1 AS `marga`,
 1 AS `adat`,
 1 AS `bpjs_ketenagakerjaan`,
 1 AS `hubung_warga`*/;
SET character_set_client = @saved_cs_client;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengaduan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_pengaduan` int DEFAULT NULL,
  `nik` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `judul` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1 = menunggu proses, 2 = Sedang Diproses, 3 = Selesai Diproses',
  `foto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip_address` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pengaduan_config_fk` (`config_id`),
  CONSTRAINT `pengaduan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permohonan_surat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_pemohon` int DEFAULT NULL,
  `id_surat` int DEFAULT NULL,
  `isian_form` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `alasan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `no_hp_aktif` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `syarat` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `no_antrian` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permohonan_surat_config_fk` (`config_id`),
  KEY `permohonan_surat_pemohon_fk` (`id_pemohon`),
  KEY `permohonan_surat_surat_fk` (`id_surat`),
  CONSTRAINT `permohonan_surat_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permohonan_surat_pemohon_fk` FOREIGN KEY (`id_pemohon`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permohonan_surat_surat_fk` FOREIGN KEY (`id_surat`) REFERENCES `tweb_surat_format` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persil` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nomor` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nomor_urut_bidang` smallint DEFAULT '1',
  `kelas` int NOT NULL,
  `luas_persil` decimal(7,0) DEFAULT NULL,
  `id_wilayah` int DEFAULT NULL,
  `lokasi` text COLLATE utf8mb4_general_ci,
  `path` text COLLATE utf8mb4_general_ci,
  `cdesa_awal` int unsigned DEFAULT NULL,
  `id_peta` int DEFAULT NULL,
  `is_publik` tinyint NOT NULL DEFAULT '1' COMMENT '1 = tampilkan di web publik, 0 = tidak ditampilkan di web publik',
  PRIMARY KEY (`id`),
  KEY `nomor_nomor_urut_bidang` (`nomor`,`nomor_urut_bidang`),
  KEY `persil_config_fk` (`config_id`),
  KEY `persil_peta_fk` (`id_peta`),
  CONSTRAINT `persil_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `persil_peta_fk` FOREIGN KEY (`id_peta`) REFERENCES `area` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `sudah_dibaca` int NOT NULL DEFAULT '1',
  `diarsipkan` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesan_config_fk` (`config_id`),
  CONSTRAINT `pesan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesan_detail` (
  `id` int unsigned NOT NULL,
  `config_id` int NOT NULL,
  `pesan_id` int DEFAULT NULL,
  `text` text COLLATE utf8mb4_general_ci NOT NULL,
  `pengirim` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_pengirim` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesan_detail_config_fk` (`config_id`),
  KEY `pesan_detail_pesan_fk` (`pesan_id`),
  CONSTRAINT `pesan_detail_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pesan_detail_pesan_fk` FOREIGN KEY (`pesan_id`) REFERENCES `pesan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesan_mandiri` (
  `uuid` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `config_id` int NOT NULL,
  `owner` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `penduduk_id` int NOT NULL,
  `subjek` tinytext COLLATE utf8mb4_general_ci,
  `komentar` text COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint DEFAULT NULL,
  `tipe` tinyint DEFAULT NULL,
  `permohonan` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_archived` tinyint DEFAULT '0',
  PRIMARY KEY (`uuid`),
  UNIQUE KEY `pesan_mandiri_uuid_config_id_unique` (`uuid`,`config_id`),
  KEY `pesan_mandiri_config_id_foreign` (`config_id`),
  KEY `pesan_mandiri_penduduk_id_foreign` (`penduduk_id`),
  CONSTRAINT `pesan_mandiri_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pesan_mandiri_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `point` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `simbol` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe` int DEFAULT '0',
  `parrent` int NOT NULL DEFAULT '1',
  `enabled` int NOT NULL DEFAULT '1',
  `sumber` enum('OpenSID','OpenKab') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'OpenSID',
  PRIMARY KEY (`id`),
  KEY `point_config_fk` (`config_id`),
  KEY `parrent` (`parrent`),
  CONSTRAINT `point_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `polygon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `simbol` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe` int DEFAULT '0',
  `parrent` int DEFAULT '1',
  `enabled` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `polygon_config_fk` (`config_id`),
  KEY `parrent` (`parrent`),
  CONSTRAINT `polygon_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posyandu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posyandu_config_fk` (`config_id`),
  CONSTRAINT `posyandu_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_pelapak` int DEFAULT NULL,
  `id_produk_kategori` int DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `satuan` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe_potongan` tinyint(1) DEFAULT '1',
  `potongan` int NOT NULL DEFAULT '0',
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `foto` varchar(225) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `produk_config_fk` (`config_id`),
  KEY `lapak_fk` (`id_pelapak`),
  KEY `produk_kategori_fk` (`id_produk_kategori`),
  CONSTRAINT `lapak_fk` FOREIGN KEY (`id_pelapak`) REFERENCES `pelapak` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `produk_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `produk_kategori_fk` FOREIGN KEY (`id_produk_kategori`) REFERENCES `produk_kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk_kategori` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `produk_kategori_config_fk` (`config_id`),
  CONSTRAINT `produk_kategori_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profil_desa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `value` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profil_desa_config_id_foreign` (`config_id`),
  CONSTRAINT `profil_desa_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `program` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sasaran` int NOT NULL,
  `kk_level` text COLLATE utf8mb4_general_ci,
  `ndesc` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sdate` date NOT NULL,
  `edate` date NOT NULL,
  `asaldana` char(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_config` (`config_id`,`slug`),
  CONSTRAINT `program_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_peserta` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `peserta` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `program_id` int DEFAULT NULL,
  `no_id_kartu` varchar(60) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kartu_nik` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `kartu_nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kartu_tempat_lahir` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `kartu_tanggal_lahir` date NOT NULL,
  `kartu_alamat` varchar(200) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `kartu_peserta` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kartu_id_pend` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `program_peserta_program_id_kartu_id_pend_unique_config` (`config_id`,`program_id`,`kartu_id_pend`),
  KEY `program_peserta_program_fk` (`program_id`),
  KEY `program_peserta_kartu_fk` (`kartu_id_pend`),
  CONSTRAINT `program_peserta_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `program_peserta_kartu_fk` FOREIGN KEY (`kartu_id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `program_peserta_program_fk` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_asal_tanah_kas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_dokumen` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_jabatan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tupoksi` longtext COLLATE utf8mb4_general_ci,
  `jenis` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ref_jabatan_config_fk` (`config_id`),
  CONSTRAINT `ref_jabatan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_penduduk_bahasa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `inisial` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_penduduk_bidang` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_penduduk_hamil` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_penduduk_kursus` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_peristiwa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_persil_kelas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tipe` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `kode` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `ndesc` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_persil_mutasi` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `ndesc` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_peruntukan_tanah_kas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_pindah` (
  `id` tinyint NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_sinkronisasi` (
  `tabel` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `server` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_update` tinyint DEFAULT NULL,
  `tabel_hapus` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`tabel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_status_covid` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_syarat_surat` (
  `ref_syarat_id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `ref_syarat_nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ref_syarat_id`),
  KEY `ref_syarat_surat_config_fk` (`config_id`),
  CONSTRAINT `ref_syarat_surat_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `rekap_mutasi_inventaris` AS SELECT 
 1 AS `asset`,
 1 AS `config_id`,
 1 AS `id_inventaris_asset`,
 1 AS `status_mutasi`,
 1 AS `jenis_mutasi`,
 1 AS `tahun_mutasi`,
 1 AS `keterangan`*/;
SET character_set_client = @saved_cs_client;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sasaran_paud` (
  `id_sasaran_paud` int unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `posyandu_id` int DEFAULT NULL,
  `kia_id` int DEFAULT NULL,
  `kategori_usia` tinyint(1) NOT NULL,
  `januari` tinyint(1) NOT NULL,
  `februari` tinyint(1) NOT NULL,
  `maret` tinyint(1) NOT NULL,
  `april` tinyint(1) NOT NULL,
  `mei` tinyint(1) NOT NULL,
  `juni` tinyint(1) NOT NULL,
  `juli` tinyint(1) NOT NULL,
  `agustus` tinyint(1) NOT NULL,
  `september` tinyint(1) NOT NULL,
  `oktober` tinyint(1) NOT NULL,
  `november` tinyint(1) NOT NULL,
  `desember` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id_sasaran_paud`),
  KEY `sasaran_paud_config_fk` (`config_id`),
  KEY `sasaran_paud_posyandu_fk` (`posyandu_id`),
  KEY `sasaran_paud_kia_fk` (`kia_id`),
  CONSTRAINT `sasaran_paud_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sasaran_paud_kia_fk` FOREIGN KEY (`kia_id`) REFERENCES `kia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sasaran_paud_posyandu_fk` FOREIGN KEY (`posyandu_id`) REFERENCES `posyandu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `security_baselines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `generated_at` timestamp NOT NULL,
  `version` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1.0',
  `target_directory` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `excluded_dirs` json DEFAULT NULL,
  `statistics` json NOT NULL,
  `files` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `security_baselines_config_id_generated_at_index` (`config_id`,`generated_at`),
  CONSTRAINT `security_baselines_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `security_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('integrity','scan') COLLATE utf8mb4_general_ci NOT NULL,
  `data` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `security_reports_config_id_type_created_at_index` (`config_id`,`type`,`created_at`),
  CONSTRAINT `security_reports_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sentitems` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `InsertIntoDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SendingDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DeliveryDateTime` timestamp NULL DEFAULT NULL,
  `Text` text COLLATE utf8mb4_general_ci NOT NULL,
  `DestinationNumber` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text COLLATE utf8mb4_general_ci NOT NULL,
  `SMSCNumber` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `Class` int NOT NULL DEFAULT '-1',
  `TextDecoded` text COLLATE utf8mb4_general_ci NOT NULL,
  `ID` int unsigned NOT NULL DEFAULT '0',
  `config_id` int NOT NULL,
  `SenderID` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `SequencePosition` int NOT NULL DEFAULT '1',
  `Status` enum('SendingOK','SendingOKNoReport','SendingError','DeliveryOK','DeliveryFailed','DeliveryPending','DeliveryUnknown','Error') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'SendingOK',
  `StatusError` int NOT NULL DEFAULT '-1',
  `TPMR` int NOT NULL DEFAULT '-1',
  `RelativeValidity` int NOT NULL DEFAULT '-1',
  `CreatorID` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID`,`SequencePosition`),
  KEY `sentitems_date_config` (`config_id`,`DeliveryDateTime`),
  KEY `sentitems_dest_config` (`config_id`,`DestinationNumber`),
  KEY `sentitems_sender_config` (`config_id`,`SenderID`),
  KEY `sentitems_tpmr_config` (`config_id`,`TPMR`),
  CONSTRAINT `sentitems_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `setting_aplikasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `judul` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `key` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `value` text COLLATE utf8mb4_general_ci,
  `urut` int DEFAULT NULL,
  `keterangan` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `option` text COLLATE utf8mb4_general_ci,
  `attribute` text COLLATE utf8mb4_general_ci,
  `kategori` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_config` (`config_id`,`key`),
  CONSTRAINT `setting_aplikasi_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `setting_modul` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `modul` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `url` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  `ikon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '',
  `urut` int DEFAULT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '0',
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `ikon_kecil` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '',
  `parent` int DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_config` (`config_id`,`slug`),
  CONSTRAINT `setting_modul_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shortcut` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `judul` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `raw_query` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `warna` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `urut` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shortcut_config_id_foreign` (`config_id`),
  CONSTRAINT `shortcut_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sinergi_program` (
  `uuid` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `config_id` int NOT NULL,
  `judul` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tautan` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `urut` int NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statistics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `url_id` int NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `statistics_config_fk` (`config_id`),
  KEY `url_id` (`url_id`),
  CONSTRAINT `statistics_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suplemen` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sasaran` tinyint DEFAULT NULL,
  `keterangan` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 = Aktif, 0 = Nonaktif',
  `sumber` enum('OpenSID','OpenKab') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'OpenSID',
  `form_isian` longtext COLLATE utf8mb4_general_ci COMMENT 'Menyimpan data formulir dinamis tambahan sebagai JSON atau teks',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_config` (`config_id`,`slug`),
  CONSTRAINT `suplemen_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suplemen_terdata` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_suplemen` int DEFAULT NULL,
  `id_terdata` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keluarga_id` int DEFAULT NULL,
  `penduduk_id` int DEFAULT NULL,
  `sasaran` tinyint DEFAULT NULL,
  `keterangan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data_form_isian` longtext COLLATE utf8mb4_general_ci COMMENT 'Menyimpan data dinamis sebagai JSON atau teks',
  PRIMARY KEY (`id`),
  KEY `suplemen_terdata_config_fk` (`config_id`),
  KEY `id_suplemen` (`id_suplemen`),
  KEY `suplemen_terdata_keluarga_fk` (`keluarga_id`),
  KEY `suplemen_terdata_penduduk_fk` (`penduduk_id`),
  CONSTRAINT `suplemen_terdata_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `suplemen_terdata_keluarga_fk` FOREIGN KEY (`keluarga_id`) REFERENCES `tweb_keluarga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `suplemen_terdata_penduduk_fk` FOREIGN KEY (`penduduk_id`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `suplemen_terdata_suplemen_fk` FOREIGN KEY (`id_suplemen`) REFERENCES `suplemen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `surat_dinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `url_surat` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_surat` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lampiran` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kunci` tinyint(1) NOT NULL DEFAULT '0',
  `favorit` tinyint(1) NOT NULL DEFAULT '0',
  `jenis` tinyint NOT NULL DEFAULT '2',
  `masa_berlaku` int DEFAULT '1',
  `satuan_masa_berlaku` varchar(15) COLLATE utf8mb4_general_ci DEFAULT 'M',
  `qr_code` tinyint(1) NOT NULL DEFAULT '0',
  `logo_garuda` tinyint(1) NOT NULL DEFAULT '0',
  `template` longtext COLLATE utf8mb4_general_ci,
  `template_desa` longtext COLLATE utf8mb4_general_ci,
  `form_isian` longtext COLLATE utf8mb4_general_ci,
  `kode_isian` longtext COLLATE utf8mb4_general_ci,
  `orientasi` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ukuran` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `margin` text COLLATE utf8mb4_general_ci,
  `margin_global` tinyint(1) DEFAULT '0',
  `footer` int NOT NULL DEFAULT '1',
  `header` int NOT NULL DEFAULT '1',
  `format_nomor` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `format_nomor_global` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_surat_dinas_config` (`config_id`,`url_surat`),
  CONSTRAINT `surat_dinas_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `surat_keluar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nomor_urut` smallint DEFAULT NULL,
  `nomor_surat` varchar(35) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_surat` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_surat` date NOT NULL,
  `tanggal_catat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tujuan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi_singkat` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `berkas_scan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ekspedisi` tinyint(1) DEFAULT '0',
  `tanggal_pengiriman` date DEFAULT NULL,
  `tanda_terima` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lokasi_arsip` varchar(150) COLLATE utf8mb4_general_ci DEFAULT '',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  `arsip_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `surat_keluar_config_fk` (`config_id`),
  CONSTRAINT `surat_keluar_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `surat_masuk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nomor_urut` smallint DEFAULT NULL,
  `tanggal_penerimaan` date NOT NULL,
  `nomor_surat` varchar(35) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_surat` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_surat` date NOT NULL,
  `pengirim` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi_singkat` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi_disposisi` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `berkas_scan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lokasi_arsip` varchar(150) COLLATE utf8mb4_general_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `surat_masuk_config_fk` (`config_id`),
  CONSTRAINT `surat_masuk_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_traffic` (
  `Tanggal` date NOT NULL,
  `config_id` int NOT NULL,
  `ipAddress` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `Jumlah` bigint NOT NULL,
  UNIQUE KEY `config_idtanggal` (`config_id`,`Tanggal`),
  CONSTRAINT `sys_traffic_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tanah_desa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `id_penduduk` int DEFAULT NULL,
  `nik` decimal(16,0) DEFAULT NULL,
  `jenis_pemilik` text COLLATE utf8mb4_general_ci,
  `nama_pemilik_asal` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `luas` int NOT NULL,
  `hak_milik` int DEFAULT NULL,
  `hak_guna_bangunan` int DEFAULT NULL,
  `hak_pakai` int DEFAULT NULL,
  `hak_guna_usaha` int DEFAULT NULL,
  `hak_pengelolaan` int DEFAULT NULL,
  `hak_milik_adat` int DEFAULT NULL,
  `hak_verponding` int DEFAULT NULL,
  `tanah_negara` int DEFAULT NULL,
  `perumahan` int DEFAULT NULL,
  `perdagangan_jasa` int DEFAULT NULL,
  `perkantoran` int DEFAULT NULL,
  `industri` int DEFAULT NULL,
  `fasilitas_umum` int DEFAULT NULL,
  `sawah` int DEFAULT NULL,
  `tegalan` int DEFAULT NULL,
  `perkebunan` int DEFAULT NULL,
  `peternakan_perikanan` int DEFAULT NULL,
  `hutan_belukar` int DEFAULT NULL,
  `hutan_lebat_lindung` int DEFAULT NULL,
  `tanah_kosong` int DEFAULT NULL,
  `lain` int DEFAULT NULL,
  `mutasi` text COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `visible` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tanah_desa_config_fk` (`config_id`),
  KEY `id_penduduk` (`id_penduduk`),
  CONSTRAINT `tanah_desa_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tanah_desa_penduduk_fk` FOREIGN KEY (`id_penduduk`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tanah_kas_desa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama_pemilik_asal` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `letter_c` text COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` text COLLATE utf8mb4_general_ci NOT NULL,
  `luas` int NOT NULL,
  `asli_milik_desa` int DEFAULT NULL,
  `pemerintah` int DEFAULT NULL,
  `provinsi` int DEFAULT NULL,
  `kabupaten_kota` int DEFAULT NULL,
  `lain_lain` int DEFAULT NULL,
  `sawah` int DEFAULT NULL,
  `tegal` int DEFAULT NULL,
  `kebun` int DEFAULT NULL,
  `tambak_kolam` int DEFAULT NULL,
  `tanah_kering_darat` int DEFAULT NULL,
  `ada_patok` int DEFAULT NULL,
  `tidak_ada_patok` int DEFAULT NULL,
  `ada_papan_nama` int DEFAULT NULL,
  `tidak_ada_papan_nama` int DEFAULT NULL,
  `tanggal_perolehan` date DEFAULT NULL,
  `lokasi` text COLLATE utf8mb4_general_ci NOT NULL,
  `peruntukan` text COLLATE utf8mb4_general_ci NOT NULL,
  `mutasi` text COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `visible` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tanah_kas_desa_config_fk` (`config_id`),
  CONSTRAINT `tanah_kas_desa_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teks_berjalan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `teks` text COLLATE utf8mb4_general_ci,
  `urut` int DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `tipe` tinyint DEFAULT '1',
  `tautan` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `judul_tautan` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teks_berjalan_config_fk` (`config_id`),
  CONSTRAINT `teks_berjalan_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `theme` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `slug` varchar(60) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `versi` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sistem` tinyint NOT NULL DEFAULT '0',
  `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `opsi` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `theme_slug_config_id_unique` (`slug`,`config_id`),
  KEY `theme_config_id_foreign` (`config_id`),
  CONSTRAINT `theme_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_aset` (
  `id_aset` int NOT NULL,
  `golongan` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `bidang` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `kelompok` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `sub_kelompok` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `sub_sub_kelompok` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_aset`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_cacat` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_cara_kb` (
  `id` tinyint NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `sex` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_desa_pamong` (
  `pamong_id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `pamong_nama` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gelar_depan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gelar_belakang` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pamong_nip` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pamong_tag_id_card` varchar(17) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pamong_pin` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pamong_nik` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pamong_status` tinyint(1) DEFAULT '1',
  `pamong_tgl_terdaftar` date DEFAULT NULL,
  `pamong_ttd` tinyint(1) DEFAULT NULL,
  `foto` text COLLATE utf8mb4_general_ci,
  `id_pend` int DEFAULT NULL,
  `pamong_tempatlahir` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pamong_tanggallahir` date DEFAULT NULL,
  `pamong_sex` tinyint DEFAULT NULL,
  `pamong_pendidikan` int DEFAULT NULL,
  `pamong_agama` int DEFAULT NULL,
  `pamong_nosk` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pamong_tglsk` date DEFAULT NULL,
  `pamong_masajab` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `urut` int DEFAULT NULL,
  `pamong_niap` varchar(25) COLLATE utf8mb4_general_ci DEFAULT '0',
  `pamong_pangkat` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pamong_nohenti` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pamong_tglhenti` date DEFAULT NULL,
  `pamong_ub` tinyint(1) NOT NULL DEFAULT '0',
  `atasan` int DEFAULT NULL,
  `bagan_tingkat` tinyint DEFAULT NULL,
  `bagan_offset` int DEFAULT NULL,
  `bagan_layout` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bagan_warna` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kehadiran` int NOT NULL DEFAULT '1',
  `jabatan_id` int DEFAULT NULL,
  `media_sosial` text COLLATE utf8mb4_general_ci,
  `status_pejabat` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`pamong_id`),
  UNIQUE KEY `pamong_tag_id_card_config` (`config_id`,`pamong_tag_id_card`),
  KEY `tweb_desa_pamong_pend_fk` (`id_pend`),
  KEY `tweb_desa_pamong_jabatan_fk` (`jabatan_id`),
  CONSTRAINT `tweb_desa_pamong_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tweb_desa_pamong_jabatan_fk` FOREIGN KEY (`jabatan_id`) REFERENCES `ref_jabatan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tweb_desa_pamong_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_golongan_darah` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_keluarga` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `no_kk` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nik_kepala` int DEFAULT NULL,
  `tgl_daftar` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `kelas_sosial` int DEFAULT NULL,
  `tgl_cetak_kk` datetime DEFAULT NULL,
  `alamat` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_cluster` int DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_kk_config` (`config_id`,`no_kk`),
  KEY `nik_kepala` (`nik_kepala`),
  KEY `tweb_keluarga_cluster_fk` (`id_cluster`),
  CONSTRAINT `tweb_keluarga_cluster_fk` FOREIGN KEY (`id_cluster`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tweb_keluarga_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tweb_keluarga_kepala_fk` FOREIGN KEY (`nik_kepala`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_keluarga_sejahtera` (
  `id` int NOT NULL DEFAULT '0',
  `nama` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_kk` int DEFAULT NULL,
  `kk_level` smallint NOT NULL,
  `id_rtm` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rtm_level` int DEFAULT NULL,
  `sex` smallint unsigned NOT NULL,
  `tempatlahir` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggallahir` date NOT NULL,
  `agama_id` int NOT NULL,
  `pendidikan_kk_id` int NOT NULL,
  `pendidikan_sedang_id` int DEFAULT NULL,
  `pekerjaan_id` int NOT NULL,
  `status_kawin` smallint NOT NULL,
  `warganegara_id` int NOT NULL DEFAULT '1',
  `dokumen_pasport` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '-',
  `dokumen_kitas` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '-',
  `ayah_nik` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ibu_nik` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_ayah` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '-',
  `nama_ibu` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '-',
  `foto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `golongan_darah_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_cluster` int DEFAULT NULL,
  `status` int unsigned DEFAULT NULL,
  `alamat_sebelumnya` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_sekarang` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_dasar` tinyint NOT NULL DEFAULT '1',
  `hamil` int DEFAULT NULL,
  `cacat_id` int DEFAULT NULL,
  `sakit_menahun_id` int DEFAULT NULL,
  `akta_lahir` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `akta_perkawinan` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggalperkawinan` date DEFAULT NULL,
  `akta_perceraian` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggalperceraian` date DEFAULT NULL,
  `cara_kb_id` tinyint DEFAULT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_akhir_paspor` date DEFAULT NULL,
  `no_kk_sebelumnya` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ktp_el` tinyint DEFAULT NULL,
  `status_rekam` tinyint DEFAULT NULL,
  `waktu_lahir` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_dilahirkan` tinyint DEFAULT NULL,
  `jenis_kelahiran` tinyint DEFAULT NULL,
  `kelahiran_anak_ke` tinyint DEFAULT NULL,
  `penolong_kelahiran` tinyint DEFAULT NULL,
  `berat_lahir` smallint DEFAULT NULL,
  `panjang_lahir` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tag_id_card` varchar(17) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_asuransi` tinyint DEFAULT NULL,
  `no_asuransi` char(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_asuransi` tinyint DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_tgl_kadaluarsa` datetime DEFAULT NULL,
  `email_tgl_verifikasi` datetime DEFAULT NULL,
  `telegram` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telegram_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telegram_tgl_kadaluarsa` datetime DEFAULT NULL,
  `telegram_tgl_verifikasi` datetime DEFAULT NULL,
  `bahasa_id` int DEFAULT NULL,
  `ket` tinytext COLLATE utf8mb4_general_ci,
  `negara_asal` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_cetak_ktp` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_cetak_ktp` date DEFAULT NULL,
  `suku` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `marga` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adat` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerja_migran` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bpjs_ketenagakerjaan` char(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hubung_warga` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nik_config` (`config_id`,`nik`),
  UNIQUE KEY `email_config` (`config_id`,`email`),
  UNIQUE KEY `email_token_config` (`config_id`,`email_token`),
  UNIQUE KEY `tag_id_card_config` (`config_id`,`tag_id_card`),
  UNIQUE KEY `telegram_config` (`config_id`,`telegram`),
  UNIQUE KEY `telegram_token_config` (`config_id`,`telegram_token`),
  KEY `tweb_penduduk_kk_fk` (`id_kk`),
  KEY `id_rtm` (`id_rtm`),
  KEY `tweb_penduduk_cluster_fk` (`id_cluster`),
  KEY `hubung_warga` (`hubung_warga`),
  CONSTRAINT `tweb_penduduk_cluster_fk` FOREIGN KEY (`id_cluster`) REFERENCES `tweb_wil_clusterdesa` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tweb_penduduk_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tweb_penduduk_kk_fk` FOREIGN KEY (`id_kk`) REFERENCES `tweb_keluarga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_agama` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_asuransi` (
  `id` tinyint NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_hubungan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_kawin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_mandiri` (
  `pin` char(255) COLLATE utf8mb4_general_ci NOT NULL,
  `config_id` int NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `tanggal_buat` datetime DEFAULT NULL,
  `id_pend` int NOT NULL AUTO_INCREMENT,
  `aktif` int DEFAULT '1',
  `scan_ktp` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `scan_kk` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_selfie` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ganti_pin` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pend`),
  KEY `tweb_penduduk_mandiri_config_fk` (`config_id`),
  CONSTRAINT `tweb_penduduk_mandiri_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tweb_penduduk_mandiri_penduduk_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_map` (
  `id` int DEFAULT NULL,
  `lat` varchar(24) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lng` varchar(24) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `config_id` int DEFAULT NULL,
  KEY `tweb_penduduk_map_pend_fk` (`id`),
  KEY `tweb_penduduk_map_config_id_foreign` (`config_id`),
  CONSTRAINT `tweb_penduduk_map_config_id_foreign` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tweb_penduduk_map_pend_fk` FOREIGN KEY (`id`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_pekerjaan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_pendidikan` (
  `id` tinyint NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_pendidikan_kk` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_sex` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_status` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_umur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dari` int DEFAULT NULL,
  `sampai` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tweb_penduduk_umur_config_fk` (`config_id`),
  CONSTRAINT `tweb_penduduk_umur_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_penduduk_warganegara` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_rtm` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nik_kepala` int DEFAULT NULL,
  `no_kk` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `kelas_sosial` int DEFAULT NULL,
  `bdt` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `terdaftar_dtks` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_kk_config` (`config_id`,`no_kk`),
  KEY `tweb_rtm_kepala_fk` (`nik_kepala`),
  KEY `idx_no_kk` (`no_kk`),
  CONSTRAINT `tweb_rtm_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tweb_rtm_kepala_fk` FOREIGN KEY (`nik_kepala`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_rtm_hubungan` (
  `id` tinyint NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_sakit_menahun` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_status_dasar` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_status_ktp` (
  `id` tinyint NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ktp_el` tinyint NOT NULL,
  `status_rekam` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_surat_format` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `url_surat` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_surat` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lampiran` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kunci` tinyint(1) NOT NULL DEFAULT '0',
  `favorit` tinyint(1) NOT NULL DEFAULT '0',
  `jenis` tinyint NOT NULL DEFAULT '2',
  `mandiri` tinyint(1) DEFAULT '0',
  `masa_berlaku` int DEFAULT '1',
  `satuan_masa_berlaku` varchar(15) COLLATE utf8mb4_general_ci DEFAULT 'M',
  `qr_code` tinyint(1) NOT NULL DEFAULT '0',
  `qr_code_tte` tinyint(1) NOT NULL DEFAULT '0',
  `logo_garuda` tinyint(1) NOT NULL DEFAULT '0',
  `kecamatan` tinyint(1) NOT NULL DEFAULT '0',
  `syarat_surat` longtext COLLATE utf8mb4_general_ci,
  `template` longtext COLLATE utf8mb4_general_ci,
  `template_desa` longtext COLLATE utf8mb4_general_ci,
  `form_isian` longtext COLLATE utf8mb4_general_ci,
  `kode_isian` longtext COLLATE utf8mb4_general_ci,
  `orientasi` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ukuran` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `margin` text COLLATE utf8mb4_general_ci,
  `margin_global` tinyint(1) DEFAULT '0',
  `footer` int NOT NULL DEFAULT '1',
  `header` int NOT NULL DEFAULT '1',
  `format_nomor` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `format_nomor_global` tinyint DEFAULT '1',
  `sumber_penduduk_berulang` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_surat_config` (`config_id`,`url_surat`),
  CONSTRAINT `tweb_surat_format_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweb_wil_clusterdesa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `rt` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `rw` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `dusun` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `id_kepala` int DEFAULT NULL,
  `lat` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lng` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zoom` int DEFAULT NULL,
  `path` text COLLATE utf8mb4_general_ci,
  `map_tipe` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `warna` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `border` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `urut` int DEFAULT NULL,
  `urut_cetak` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rt_config` (`config_id`,`rt`,`rw`,`dusun`),
  KEY `id_kepala` (`id_kepala`),
  CONSTRAINT `tweb_wil_clusterdesa_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tweb_wil_clusterdesa_kepala_fk` FOREIGN KEY (`id_kepala`) REFERENCES `tweb_penduduk` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `urls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `urls_config_fk` (`config_id`),
  KEY `alias` (`alias`),
  CONSTRAINT `urls_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int DEFAULT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_grup` int DEFAULT NULL,
  `pamong_id` int DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `active` tinyint unsigned DEFAULT '0',
  `otp_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `otp_channel` enum('email','telegram','both') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `otp_identifier` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telegram_chat_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_telegram` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `token_exp` datetime DEFAULT NULL,
  `telegram_verified_at` datetime DEFAULT NULL,
  `notif_telegram` tinyint(1) NOT NULL DEFAULT '0',
  `company` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'kuser.png',
  `session` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `batasi_wilayah` tinyint unsigned NOT NULL DEFAULT '0',
  `akses_wilayah` text COLLATE utf8mb4_general_ci,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_config` (`config_id`,`email`),
  UNIQUE KEY `pamong_id_config` (`config_id`,`pamong_id`),
  UNIQUE KEY `username_config` (`config_id`,`username`),
  KEY `user_grup_fk` (`id_grup`),
  KEY `user_pamong_fk` (`pamong_id`),
  CONSTRAINT `user_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_grup_fk` FOREIGN KEY (`id_grup`) REFERENCES `user_grup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_pamong_fk` FOREIGN KEY (`pamong_id`) REFERENCES `tweb_desa_pamong` (`pamong_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_grup` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis` tinyint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_grup_config` (`config_id`,`nama`),
  UNIQUE KEY `slug_config` (`config_id`,`slug`),
  CONSTRAINT `user_grup_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `widget` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_id` int NOT NULL,
  `isi` text COLLATE utf8mb4_general_ci,
  `enabled` int DEFAULT NULL,
  `judul` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_widget` tinyint NOT NULL DEFAULT '3',
  `urut` int DEFAULT NULL,
  `form_admin` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `setting` text COLLATE utf8mb4_general_ci,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `widget_config_fk` (`config_id`),
  CONSTRAINT `widget_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50001 DROP VIEW IF EXISTS `dokumen_hidup`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `dokumen_hidup` AS select `dokumen`.`id` AS `id`,`dokumen`.`config_id` AS `config_id`,`dokumen`.`satuan` AS `satuan`,`dokumen`.`nama` AS `nama`,`dokumen`.`enabled` AS `enabled`,`dokumen`.`tgl_upload` AS `tgl_upload`,`dokumen`.`id_pend` AS `id_pend`,`dokumen`.`kategori` AS `kategori`,`dokumen`.`attr` AS `attr`,`dokumen`.`tipe` AS `tipe`,`dokumen`.`url` AS `url`,`dokumen`.`tahun` AS `tahun`,`dokumen`.`kategori_info_publik` AS `kategori_info_publik`,`dokumen`.`updated_at` AS `updated_at`,`dokumen`.`deleted` AS `deleted`,`dokumen`.`id_syarat` AS `id_syarat`,`dokumen`.`id_parent` AS `id_parent`,`dokumen`.`created_at` AS `created_at`,`dokumen`.`created_by` AS `created_by`,`dokumen`.`updated_by` AS `updated_by`,`dokumen`.`dok_warga` AS `dok_warga`,`dokumen`.`lokasi_arsip` AS `lokasi_arsip`,`dokumen`.`keterangan` AS `keterangan`,`dokumen`.`status` AS `status`,`dokumen`.`retensi_date` AS `retensi_date`,`dokumen`.`retensi_number` AS `retensi_number`,`dokumen`.`retensi_unit` AS `retensi_unit`,`dokumen`.`published_at` AS `published_at` from `dokumen` where (`dokumen`.`deleted` <> 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `keluarga_aktif`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `keluarga_aktif` AS select `k`.`id` AS `id`,`k`.`config_id` AS `config_id`,`k`.`no_kk` AS `no_kk`,`k`.`nik_kepala` AS `nik_kepala`,`k`.`tgl_daftar` AS `tgl_daftar`,`k`.`kelas_sosial` AS `kelas_sosial`,`k`.`tgl_cetak_kk` AS `tgl_cetak_kk`,`k`.`alamat` AS `alamat`,`k`.`id_cluster` AS `id_cluster`,`k`.`updated_at` AS `updated_at`,`k`.`updated_by` AS `updated_by` from (`tweb_keluarga` `k` left join `tweb_penduduk` `p` on((`k`.`nik_kepala` = `p`.`id`))) where (`p`.`status_dasar` = 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `master_inventaris`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `master_inventaris` AS select 'inventaris_asset' AS `asset`,`inventaris_asset`.`config_id` AS `config_id`,`inventaris_asset`.`id` AS `id`,`inventaris_asset`.`nama_barang` AS `nama_barang`,`inventaris_asset`.`kode_barang` AS `kode_barang`,'Baik' AS `kondisi`,`inventaris_asset`.`keterangan` AS `keterangan`,`inventaris_asset`.`asal` AS `asal`,`inventaris_asset`.`tahun_pengadaan` AS `tahun_pengadaan` from `inventaris_asset` where (`inventaris_asset`.`visible` = 1) union all select 'inventaris_gedung' AS `asset`,`inventaris_gedung`.`config_id` AS `config_id`,`inventaris_gedung`.`id` AS `id`,`inventaris_gedung`.`nama_barang` AS `nama_barang`,`inventaris_gedung`.`kode_barang` AS `kode_barang`,`inventaris_gedung`.`kondisi_bangunan` AS `kondisi_bangunan`,`inventaris_gedung`.`keterangan` AS `keterangan`,`inventaris_gedung`.`asal` AS `asal`,year(`inventaris_gedung`.`tanggal_dokument`) AS `tahun_pengadaan` from `inventaris_gedung` where (`inventaris_gedung`.`visible` = 1) union all select 'inventaris_jalan' AS `asset`,`inventaris_jalan`.`config_id` AS `config_id`,`inventaris_jalan`.`id` AS `id`,`inventaris_jalan`.`nama_barang` AS `nama_barang`,`inventaris_jalan`.`kode_barang` AS `kode_barang`,`inventaris_jalan`.`kondisi` AS `kondisi`,`inventaris_jalan`.`keterangan` AS `keterangan`,`inventaris_jalan`.`asal` AS `asal`,year(`inventaris_jalan`.`tanggal_dokument`) AS `tahun_pengadaan` from `inventaris_jalan` where (`inventaris_jalan`.`visible` = 1) union all select 'inventaris_peralatan' AS `asset`,`inventaris_peralatan`.`config_id` AS `config_id`,`inventaris_peralatan`.`id` AS `id`,`inventaris_peralatan`.`nama_barang` AS `nama_barang`,`inventaris_peralatan`.`kode_barang` AS `kode_barang`,'Baik' AS `Baik`,`inventaris_peralatan`.`keterangan` AS `keterangan`,`inventaris_peralatan`.`asal` AS `asal`,`inventaris_peralatan`.`tahun_pengadaan` AS `tahun_pengadaan` from `inventaris_peralatan` where (`inventaris_peralatan`.`visible` = 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `penduduk_hidup`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `penduduk_hidup` AS select `tweb_penduduk`.`id` AS `id`,`tweb_penduduk`.`config_id` AS `config_id`,`tweb_penduduk`.`nama` AS `nama`,`tweb_penduduk`.`nik` AS `nik`,`tweb_penduduk`.`id_kk` AS `id_kk`,`tweb_penduduk`.`kk_level` AS `kk_level`,`tweb_penduduk`.`id_rtm` AS `id_rtm`,`tweb_penduduk`.`rtm_level` AS `rtm_level`,`tweb_penduduk`.`sex` AS `sex`,`tweb_penduduk`.`tempatlahir` AS `tempatlahir`,`tweb_penduduk`.`tanggallahir` AS `tanggallahir`,`tweb_penduduk`.`agama_id` AS `agama_id`,`tweb_penduduk`.`pendidikan_kk_id` AS `pendidikan_kk_id`,`tweb_penduduk`.`pendidikan_sedang_id` AS `pendidikan_sedang_id`,`tweb_penduduk`.`pekerjaan_id` AS `pekerjaan_id`,`tweb_penduduk`.`status_kawin` AS `status_kawin`,`tweb_penduduk`.`warganegara_id` AS `warganegara_id`,`tweb_penduduk`.`dokumen_pasport` AS `dokumen_pasport`,`tweb_penduduk`.`dokumen_kitas` AS `dokumen_kitas`,`tweb_penduduk`.`ayah_nik` AS `ayah_nik`,`tweb_penduduk`.`ibu_nik` AS `ibu_nik`,`tweb_penduduk`.`nama_ayah` AS `nama_ayah`,`tweb_penduduk`.`nama_ibu` AS `nama_ibu`,`tweb_penduduk`.`foto` AS `foto`,`tweb_penduduk`.`golongan_darah_id` AS `golongan_darah_id`,`tweb_penduduk`.`id_cluster` AS `id_cluster`,`tweb_penduduk`.`status` AS `status`,`tweb_penduduk`.`alamat_sebelumnya` AS `alamat_sebelumnya`,`tweb_penduduk`.`alamat_sekarang` AS `alamat_sekarang`,`tweb_penduduk`.`status_dasar` AS `status_dasar`,`tweb_penduduk`.`hamil` AS `hamil`,`tweb_penduduk`.`cacat_id` AS `cacat_id`,`tweb_penduduk`.`sakit_menahun_id` AS `sakit_menahun_id`,`tweb_penduduk`.`akta_lahir` AS `akta_lahir`,`tweb_penduduk`.`akta_perkawinan` AS `akta_perkawinan`,`tweb_penduduk`.`tanggalperkawinan` AS `tanggalperkawinan`,`tweb_penduduk`.`akta_perceraian` AS `akta_perceraian`,`tweb_penduduk`.`tanggalperceraian` AS `tanggalperceraian`,`tweb_penduduk`.`cara_kb_id` AS `cara_kb_id`,`tweb_penduduk`.`telepon` AS `telepon`,`tweb_penduduk`.`tanggal_akhir_paspor` AS `tanggal_akhir_paspor`,`tweb_penduduk`.`no_kk_sebelumnya` AS `no_kk_sebelumnya`,`tweb_penduduk`.`ktp_el` AS `ktp_el`,`tweb_penduduk`.`status_rekam` AS `status_rekam`,`tweb_penduduk`.`waktu_lahir` AS `waktu_lahir`,`tweb_penduduk`.`tempat_dilahirkan` AS `tempat_dilahirkan`,`tweb_penduduk`.`jenis_kelahiran` AS `jenis_kelahiran`,`tweb_penduduk`.`kelahiran_anak_ke` AS `kelahiran_anak_ke`,`tweb_penduduk`.`penolong_kelahiran` AS `penolong_kelahiran`,`tweb_penduduk`.`berat_lahir` AS `berat_lahir`,`tweb_penduduk`.`panjang_lahir` AS `panjang_lahir`,`tweb_penduduk`.`tag_id_card` AS `tag_id_card`,`tweb_penduduk`.`created_at` AS `created_at`,`tweb_penduduk`.`created_by` AS `created_by`,`tweb_penduduk`.`updated_at` AS `updated_at`,`tweb_penduduk`.`updated_by` AS `updated_by`,`tweb_penduduk`.`id_asuransi` AS `id_asuransi`,`tweb_penduduk`.`no_asuransi` AS `no_asuransi`,`tweb_penduduk`.`email` AS `email`,`tweb_penduduk`.`email_token` AS `email_token`,`tweb_penduduk`.`email_tgl_kadaluarsa` AS `email_tgl_kadaluarsa`,`tweb_penduduk`.`email_tgl_verifikasi` AS `email_tgl_verifikasi`,`tweb_penduduk`.`telegram` AS `telegram`,`tweb_penduduk`.`telegram_token` AS `telegram_token`,`tweb_penduduk`.`telegram_tgl_kadaluarsa` AS `telegram_tgl_kadaluarsa`,`tweb_penduduk`.`telegram_tgl_verifikasi` AS `telegram_tgl_verifikasi`,`tweb_penduduk`.`bahasa_id` AS `bahasa_id`,`tweb_penduduk`.`ket` AS `ket`,`tweb_penduduk`.`negara_asal` AS `negara_asal`,`tweb_penduduk`.`tempat_cetak_ktp` AS `tempat_cetak_ktp`,`tweb_penduduk`.`tanggal_cetak_ktp` AS `tanggal_cetak_ktp`,`tweb_penduduk`.`pekerja_migran` AS `pekerja_migran`,`tweb_penduduk`.`suku` AS `suku`,`tweb_penduduk`.`marga` AS `marga`,`tweb_penduduk`.`adat` AS `adat`,`tweb_penduduk`.`bpjs_ketenagakerjaan` AS `bpjs_ketenagakerjaan`,`tweb_penduduk`.`hubung_warga` AS `hubung_warga` from `tweb_penduduk` where (`tweb_penduduk`.`status_dasar` = 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `rekap_mutasi_inventaris`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rekap_mutasi_inventaris` AS select 'inventaris_asset' AS `asset`,`mutasi_inventaris_asset`.`config_id` AS `config_id`,`mutasi_inventaris_asset`.`id_inventaris_asset` AS `id_inventaris_asset`,`mutasi_inventaris_asset`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_asset`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_asset`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_asset`.`keterangan` AS `keterangan` from `mutasi_inventaris_asset` where (`mutasi_inventaris_asset`.`visible` = 1) union all select 'inventaris_gedung' AS `inventaris_gedung`,`mutasi_inventaris_gedung`.`config_id` AS `config_id`,`mutasi_inventaris_gedung`.`id_inventaris_gedung` AS `id_inventaris_gedung`,`mutasi_inventaris_gedung`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_gedung`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_gedung`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_gedung`.`keterangan` AS `keterangan` from `mutasi_inventaris_gedung` where (`mutasi_inventaris_gedung`.`visible` = 1) union all select 'inventaris_jalan' AS `inventaris_jalan`,`mutasi_inventaris_jalan`.`config_id` AS `config_id`,`mutasi_inventaris_jalan`.`id_inventaris_jalan` AS `id_inventaris_jalan`,`mutasi_inventaris_jalan`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_jalan`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_jalan`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_jalan`.`keterangan` AS `keterangan` from `mutasi_inventaris_jalan` where (`mutasi_inventaris_jalan`.`visible` = 1) union all select 'inventaris_peralatan' AS `inventaris_peralatan`,`mutasi_inventaris_peralatan`.`config_id` AS `config_id`,`mutasi_inventaris_peralatan`.`id_inventaris_peralatan` AS `id_inventaris_peralatan`,`mutasi_inventaris_peralatan`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_peralatan`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_peralatan`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_peralatan`.`keterangan` AS `keterangan` from `mutasi_inventaris_peralatan` where (`mutasi_inventaris_peralatan`.`visible` = 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

