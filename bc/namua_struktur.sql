-- MySQL dump 10.13  Distrib 5.7.40, for Linux (x86_64)
--
-- Host: 89.116.171.157    Database: namua
-- ------------------------------------------------------
-- Server version	5.7.40-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `abs_absensi`
--

DROP TABLE IF EXISTS `abs_absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_absensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `shift_id` int(11) NOT NULL,
  `jenis_absen` enum('masuk','pulang') NOT NULL,
  `lokasi_id` int(11) DEFAULT NULL,
  `verified_by` int(11) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pegawai_id` (`pegawai_id`),
  CONSTRAINT `abs_absensi_ibfk_1` FOREIGN KEY (`pegawai_id`) REFERENCES `abs_pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3627 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_absensi_pending`
--

DROP TABLE IF EXISTS `abs_absensi_pending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_absensi_pending` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `jenis_absen` enum('masuk','pulang') NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `verified_by` int(11) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1320 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_arsip_gaji`
--

DROP TABLE IF EXISTS `abs_arsip_gaji`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_arsip_gaji` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal_awal` date NOT NULL,
  `tanggal_akhir` date NOT NULL,
  `pegawai_id` int(11) NOT NULL,
  `nama_pegawai` varchar(255) NOT NULL,
  `nomor_rekening` varchar(50) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `divisi` varchar(100) DEFAULT NULL,
  `jabatan1` varchar(100) DEFAULT NULL,
  `jabatan2` varchar(100) DEFAULT NULL,
  `total_kehadiran` int(11) DEFAULT NULL,
  `total_menit` int(11) DEFAULT NULL,
  `total_jam` decimal(10,2) DEFAULT NULL,
  `gaji_pokok` decimal(15,2) DEFAULT NULL,
  `tunjangan` decimal(15,2) DEFAULT NULL,
  `total_lembur` decimal(15,2) DEFAULT NULL,
  `tambahan_lain` decimal(15,2) DEFAULT NULL,
  `potongan` decimal(15,2) DEFAULT NULL,
  `deposit` decimal(15,2) DEFAULT NULL,
  `bayar_kasbon` decimal(15,2) DEFAULT NULL,
  `total_penerimaan` decimal(15,2) DEFAULT NULL,
  `pembulatan_penerimaan` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_deposit`
--

DROP TABLE IF EXISTS `abs_deposit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nilai` decimal(10,2) NOT NULL,
  `jenis` enum('setor','tarik') NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pegawai_id` (`pegawai_id`),
  CONSTRAINT `abs_deposit_ibfk_1` FOREIGN KEY (`pegawai_id`) REFERENCES `abs_pegawai` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_divisi`
--

DROP TABLE IF EXISTS `abs_divisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_divisi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_jabatan`
--

DROP TABLE IF EXISTS `abs_jabatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_jabatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `divisi_id` int(11) NOT NULL,
  `nama_jabatan` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `divisi_id` (`divisi_id`),
  CONSTRAINT `abs_jabatan_ibfk_1` FOREIGN KEY (`divisi_id`) REFERENCES `abs_divisi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_jadwal_shift`
--

DROP TABLE IF EXISTS `abs_jadwal_shift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_jadwal_shift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pegawai_id` (`pegawai_id`),
  KEY `shift_id` (`shift_id`),
  CONSTRAINT `abs_jadwal_shift_ibfk_1` FOREIGN KEY (`pegawai_id`) REFERENCES `abs_pegawai` (`id`),
  CONSTRAINT `abs_jadwal_shift_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `abs_shift` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2039 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_kasbon`
--

DROP TABLE IF EXISTS `abs_kasbon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_kasbon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nilai` decimal(15,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `jenis` enum('kasbon','bayar') NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pegawai_id` (`pegawai_id`),
  CONSTRAINT `abs_kasbon_ibfk_1` FOREIGN KEY (`pegawai_id`) REFERENCES `abs_pegawai` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_lembur`
--

DROP TABLE IF EXISTS `abs_lembur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_lembur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `lama_lembur` int(11) NOT NULL,
  `alasan` text,
  `nilai_lembur_id` int(11) DEFAULT NULL,
  `total_gaji_lembur` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pegawai_id` (`pegawai_id`),
  CONSTRAINT `abs_lembur_ibfk_1` FOREIGN KEY (`pegawai_id`) REFERENCES `abs_pegawai` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=224 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_lokasi_absensi`
--

DROP TABLE IF EXISTS `abs_lokasi_absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_lokasi_absensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lokasi` varchar(100) NOT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `range` int(11) DEFAULT '100',
  `status` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_nilai_lembur`
--

DROP TABLE IF EXISTS `abs_nilai_lembur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_nilai_lembur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nilai_per_jam` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_pegawai`
--

DROP TABLE IF EXISTS `abs_pegawai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_pegawai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `divisi_id` int(11) NOT NULL,
  `jabatan1_id` int(11) NOT NULL,
  `jabatan2_id` int(11) DEFAULT NULL,
  `gaji_pokok` decimal(15,2) DEFAULT NULL,
  `gaji_per_jam` decimal(15,2) DEFAULT NULL,
  `tunjangan` decimal(15,2) DEFAULT NULL,
  `tanggal_kontrak_awal` date DEFAULT NULL,
  `durasi_kontrak` int(11) DEFAULT NULL,
  `tambahan_lain` decimal(15,2) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `kode_user` varchar(20) NOT NULL DEFAULT 'pegawai',
  `tanggal_kontrak_akhir` date DEFAULT NULL,
  `nomor_rekening` varchar(50) DEFAULT NULL,
  `nama_bank_id` int(11) DEFAULT NULL,
  `is_kasir` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_divisi` (`divisi_id`),
  KEY `fk_jabatan1` (`jabatan1_id`),
  KEY `fk_jabatan2` (`jabatan2_id`),
  KEY `fk_bank_id` (`nama_bank_id`),
  CONSTRAINT `abs_pegawai_ibfk_1` FOREIGN KEY (`divisi_id`) REFERENCES `abs_divisi` (`id`),
  CONSTRAINT `abs_pegawai_ibfk_2` FOREIGN KEY (`jabatan1_id`) REFERENCES `abs_jabatan` (`id`),
  CONSTRAINT `abs_pegawai_ibfk_3` FOREIGN KEY (`jabatan2_id`) REFERENCES `abs_jabatan` (`id`),
  CONSTRAINT `fk_bank_id` FOREIGN KEY (`nama_bank_id`) REFERENCES `abs_rekening_bank` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_potongan`
--

DROP TABLE IF EXISTS `abs_potongan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_potongan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nilai` decimal(10,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pegawai_id` (`pegawai_id`),
  CONSTRAINT `abs_potongan_ibfk_1` FOREIGN KEY (`pegawai_id`) REFERENCES `abs_pegawai` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_rekap_absensi`
--

DROP TABLE IF EXISTS `abs_rekap_absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_rekap_absensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `pegawai_id` int(11) NOT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `terlambat` int(11) DEFAULT '0',
  `pulang_cepat` int(11) DEFAULT '0',
  `lama_menit_kerja` int(11) DEFAULT '0',
  `total_gaji` decimal(10,2) DEFAULT '0.00',
  `verified_by` int(11) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pegawai_id` (`pegawai_id`),
  KEY `rekap_absensi_ibfk_2` (`shift_id`),
  CONSTRAINT `abs_rekap_absensi_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `abs_shift` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1942 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_rekening_bank`
--

DROP TABLE IF EXISTS `abs_rekening_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_rekening_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_bank` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_shift`
--

DROP TABLE IF EXISTS `abs_shift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_shift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `divisi_id` int(11) NOT NULL,
  `kode_shift` varchar(50) NOT NULL,
  `nama_shift` varchar(255) DEFAULT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `divisi_id` (`divisi_id`),
  CONSTRAINT `abs_shift_ibfk_1` FOREIGN KEY (`divisi_id`) REFERENCES `abs_divisi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abs_tambahan_lain`
--

DROP TABLE IF EXISTS `abs_tambahan_lain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abs_tambahan_lain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nilai_tambahan` decimal(10,2) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pegawai_id` (`pegawai_id`),
  CONSTRAINT `abs_tambahan_lain_ibfk_1` FOREIGN KEY (`pegawai_id`) REFERENCES `abs_pegawai` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aset_data`
--

DROP TABLE IF EXISTS `aset_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aset_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_aset` varchar(255) NOT NULL,
  `kode_aset` varchar(100) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `kondisi` varchar(100) DEFAULT 'Baik',
  `status` varchar(100) DEFAULT 'Aktif',
  `tanggal_perolehan` date DEFAULT NULL,
  `nilai_beli` decimal(15,2) DEFAULT NULL,
  `nilai_penyusutan` decimal(15,2) DEFAULT '0.00',
  `catatan` text,
  `lampiran` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `divisi_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_aset` (`kode_aset`),
  KEY `kategori_id` (`kategori_id`),
  KEY `divisi_id` (`divisi_id`),
  CONSTRAINT `aset_data_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `aset_kategori` (`id`),
  CONSTRAINT `aset_data_ibfk_2` FOREIGN KEY (`divisi_id`) REFERENCES `aset_divisi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aset_divisi`
--

DROP TABLE IF EXISTS `aset_divisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aset_divisi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(100) NOT NULL,
  `keterangan` text,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aset_kategori`
--

DROP TABLE IF EXISTS `aset_kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aset_kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `divisi_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `divisi_id` (`divisi_id`),
  CONSTRAINT `aset_kategori_ibfk_1` FOREIGN KEY (`divisi_id`) REFERENCES `aset_divisi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aset_lampiran`
--

DROP TABLE IF EXISTS `aset_lampiran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aset_lampiran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aset_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `aset_id` (`aset_id`),
  CONSTRAINT `aset_lampiran_ibfk_1` FOREIGN KEY (`aset_id`) REFERENCES `aset_data` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aset_penghapusan`
--

DROP TABLE IF EXISTS `aset_penghapusan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aset_penghapusan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aset_id` int(11) NOT NULL,
  `tanggal_penghapusan` date DEFAULT NULL,
  `alasan` text,
  `nilai_sisa` decimal(15,2) DEFAULT NULL,
  `metode_penghapusan` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `aset_id` (`aset_id`),
  CONSTRAINT `aset_penghapusan_ibfk_1` FOREIGN KEY (`aset_id`) REFERENCES `aset_data` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aset_riwayat`
--

DROP TABLE IF EXISTS `aset_riwayat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aset_riwayat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aset_id` int(11) NOT NULL,
  `aktivitas` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `aset_id` (`aset_id`),
  CONSTRAINT `aset_riwayat_ibfk_1` FOREIGN KEY (`aset_id`) REFERENCES `aset_data` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_daily_bar`
--

DROP TABLE IF EXISTS `bl_daily_bar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_daily_bar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal_pembelian` date NOT NULL,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `hpp` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_daily_inventory`
--

DROP TABLE IF EXISTS `bl_daily_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_daily_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal_pembelian` date NOT NULL,
  `bl_purchase_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_purchase_id` (`bl_purchase_id`),
  CONSTRAINT `bl_daily_inventory_ibfk_1` FOREIGN KEY (`bl_purchase_id`) REFERENCES `bl_purchase` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_daily_kitchen`
--

DROP TABLE IF EXISTS `bl_daily_kitchen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_daily_kitchen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal_pembelian` date NOT NULL,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `hpp` decimal(15,2) NOT NULL,
  `hpp_average` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_db_belanja`
--

DROP TABLE IF EXISTS `bl_db_belanja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_db_belanja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(100) NOT NULL,
  `nama_bahan_baku` varchar(100) DEFAULT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_tipe_produksi` int(11) NOT NULL,
  `is_gudang` int(11) DEFAULT NULL,
  `tanggal_update` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_kategori` (`id_kategori`),
  KEY `id_tipe_produksi` (`id_tipe_produksi`),
  CONSTRAINT `bl_db_belanja_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `bl_kategori` (`id`),
  CONSTRAINT `bl_db_belanja_ibfk_2` FOREIGN KEY (`id_tipe_produksi`) REFERENCES `bl_tipe_produksi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=546 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_db_purchase`
--

DROP TABLE IF EXISTS `bl_db_purchase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_db_purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bl_db_belanja_id` int(11) NOT NULL,
  `merk` varchar(100) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `ukuran` float NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `pack` varchar(50) DEFAULT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `hpp` decimal(10,2) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_db_belanja_id` (`bl_db_belanja_id`),
  CONSTRAINT `bl_db_purchase_ibfk_1` FOREIGN KEY (`bl_db_belanja_id`) REFERENCES `bl_db_belanja` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1990 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_divisi`
--

DROP TABLE IF EXISTS `bl_divisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_divisi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_gudang`
--

DROP TABLE IF EXISTS `bl_gudang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_gudang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `stok_awal` decimal(10,2) DEFAULT '0.00',
  `stok_masuk` decimal(10,2) DEFAULT '0.00',
  `stok_keluar` decimal(10,2) DEFAULT '0.00',
  `stok_terbuang` decimal(10,2) DEFAULT '0.00',
  `stok_penyesuaian` decimal(10,2) DEFAULT '0.00',
  `stok_akhir` decimal(10,2) DEFAULT '0.00',
  `tanggal` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_db_belanja_id` (`bl_db_belanja_id`),
  KEY `bl_db_purchase_id` (`bl_db_purchase_id`),
  CONSTRAINT `bl_gudang_ibfk_1` FOREIGN KEY (`bl_db_belanja_id`) REFERENCES `bl_db_belanja` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bl_gudang_ibfk_2` FOREIGN KEY (`bl_db_purchase_id`) REFERENCES `bl_db_purchase` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1424 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_gudang_bc 250121`
--

DROP TABLE IF EXISTS `bl_gudang_bc 250121`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_gudang_bc 250121` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `stok_awal` decimal(10,2) DEFAULT '0.00',
  `stok_masuk` decimal(10,2) DEFAULT '0.00',
  `stok_keluar` decimal(10,2) DEFAULT '0.00',
  `stok_terbuang` decimal(10,2) DEFAULT '0.00',
  `stok_penyesuaian` decimal(10,2) DEFAULT '0.00',
  `stok_akhir` decimal(10,2) DEFAULT '0.00',
  `tanggal` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_db_belanja_id` (`bl_db_belanja_id`),
  KEY `bl_db_purchase_id` (`bl_db_purchase_id`),
  CONSTRAINT `bl_gudang_bc 250121_ibfk_1` FOREIGN KEY (`bl_db_belanja_id`) REFERENCES `bl_db_belanja` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bl_gudang_bc 250121_ibfk_2` FOREIGN KEY (`bl_db_purchase_id`) REFERENCES `bl_db_purchase` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_gudang_januari`
--

DROP TABLE IF EXISTS `bl_gudang_januari`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_gudang_januari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `stok_awal` decimal(10,2) DEFAULT '0.00',
  `stok_masuk` decimal(10,2) DEFAULT '0.00',
  `stok_keluar` decimal(10,2) DEFAULT '0.00',
  `stok_terbuang` decimal(10,2) DEFAULT '0.00',
  `stok_penyesuaian` decimal(10,2) DEFAULT '0.00',
  `stok_akhir` decimal(10,2) DEFAULT '0.00',
  `tanggal` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_db_belanja_id` (`bl_db_belanja_id`),
  KEY `bl_db_purchase_id` (`bl_db_purchase_id`),
  CONSTRAINT `bl_gudang_januari_ibfk_1` FOREIGN KEY (`bl_db_belanja_id`) REFERENCES `bl_db_belanja` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bl_gudang_januari_ibfk_2` FOREIGN KEY (`bl_db_purchase_id`) REFERENCES `bl_db_purchase` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=578 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_jenis_pengeluaran`
--

DROP TABLE IF EXISTS `bl_jenis_pengeluaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_jenis_pengeluaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jenis_pengeluaran` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_kas`
--

DROP TABLE IF EXISTS `bl_kas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_kas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bl_rekening_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unik_bl_rekening_tanggal` (`bl_rekening_id`,`tanggal`),
  CONSTRAINT `bl_kas_ibfk_1` FOREIGN KEY (`bl_rekening_id`) REFERENCES `bl_rekening` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_kategori`
--

DROP TABLE IF EXISTS `bl_kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_mutasi_kas`
--

DROP TABLE IF EXISTS `bl_mutasi_kas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_mutasi_kas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `bl_rekening_id` int(11) NOT NULL,
  `jenis_mutasi` enum('masuk','keluar') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `keterangan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_rekening_id` (`bl_rekening_id`),
  CONSTRAINT `bl_mutasi_kas_ibfk_1` FOREIGN KEY (`bl_rekening_id`) REFERENCES `bl_rekening` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_mutasi_kas_rekening`
--

DROP TABLE IF EXISTS `bl_mutasi_kas_rekening`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_mutasi_kas_rekening` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `bl_rekening_id_sumber` int(11) NOT NULL,
  `bl_rekening_id_tujuan` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_rekening_id_sumber` (`bl_rekening_id_sumber`),
  KEY `bl_rekening_id_tujuan` (`bl_rekening_id_tujuan`),
  CONSTRAINT `bl_mutasi_kas_rekening_ibfk_1` FOREIGN KEY (`bl_rekening_id_sumber`) REFERENCES `bl_rekening` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bl_mutasi_kas_rekening_ibfk_2` FOREIGN KEY (`bl_rekening_id_tujuan`) REFERENCES `bl_rekening` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_penjualan_majoo`
--

DROP TABLE IF EXISTS `bl_penjualan_majoo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_penjualan_majoo` (
  `tanggal` date NOT NULL,
  `no_nota` varchar(50) NOT NULL,
  `waktu_order` varchar(50) NOT NULL,
  `waktu_bayar` varchar(50) NOT NULL,
  `outlet` varchar(100) NOT NULL,
  `jenis_order` varchar(100) NOT NULL,
  `penjualan` decimal(15,2) NOT NULL,
  `metode_pembayaran` varchar(100) NOT NULL,
  `rekening_id` int(11) DEFAULT NULL,
  `penyesuaian` int(10) DEFAULT NULL,
  `selisih` int(10) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `date` (`tanggal`,`no_nota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_penjualan_produk`
--

DROP TABLE IF EXISTS `bl_penjualan_produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_penjualan_produk` (
  `tanggal` date NOT NULL,
  `produk` varchar(255) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `jenis_produk` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `nilai` decimal(15,2) NOT NULL,
  `jumlah_refund` int(11) NOT NULL,
  `nilai_refund` decimal(15,2) NOT NULL,
  `penjualan` decimal(15,2) GENERATED ALWAYS AS ((`nilai` - `nilai_refund`)) VIRTUAL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_tanggal_sku` (`tanggal`,`sku`),
  KEY `tanggal` (`tanggal`,`produk`,`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_penyesuaian`
--

DROP TABLE IF EXISTS `bl_penyesuaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_penyesuaian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `alasan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_db_belanja_id` (`bl_db_belanja_id`),
  KEY `bl_db_purchase_id` (`bl_db_purchase_id`),
  CONSTRAINT `bl_penyesuaian_ibfk_1` FOREIGN KEY (`bl_db_belanja_id`) REFERENCES `bl_db_belanja` (`id`),
  CONSTRAINT `bl_penyesuaian_ibfk_2` FOREIGN KEY (`bl_db_purchase_id`) REFERENCES `bl_db_purchase` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_persediaan_awal`
--

DROP TABLE IF EXISTS `bl_persediaan_awal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_persediaan_awal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `total_unit` decimal(10,2) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `hpp` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_db_belanja_id` (`bl_db_belanja_id`),
  KEY `bl_db_purchase_id` (`bl_db_purchase_id`),
  CONSTRAINT `bl_persediaan_awal_ibfk_1` FOREIGN KEY (`bl_db_belanja_id`) REFERENCES `bl_db_belanja` (`id`),
  CONSTRAINT `bl_persediaan_awal_ibfk_2` FOREIGN KEY (`bl_db_purchase_id`) REFERENCES `bl_db_purchase` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_produk`
--

DROP TABLE IF EXISTS `bl_produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `satuan` varchar(100) NOT NULL,
  `harga_jual` decimal(15,2) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `divisi_id` int(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=319 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_purchase`
--

DROP TABLE IF EXISTS `bl_purchase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `jenis_pengeluaran` int(11) NOT NULL,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `total_unit` float NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `hpp` decimal(10,2) NOT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `pengusul` enum('purchase','bar','kitchen') NOT NULL,
  `metode_pembayaran` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `jenis_pengeluaran` (`jenis_pengeluaran`),
  KEY `bl_db_belanja_id` (`bl_db_belanja_id`),
  KEY `bl_db_purchase_id` (`bl_db_purchase_id`),
  KEY `metode_pembayaran` (`metode_pembayaran`),
  CONSTRAINT `bl_purchase_ibfk_1` FOREIGN KEY (`jenis_pengeluaran`) REFERENCES `bl_jenis_pengeluaran` (`id`),
  CONSTRAINT `bl_purchase_ibfk_2` FOREIGN KEY (`bl_db_belanja_id`) REFERENCES `bl_db_belanja` (`id`),
  CONSTRAINT `bl_purchase_ibfk_3` FOREIGN KEY (`bl_db_purchase_id`) REFERENCES `bl_db_purchase` (`id`),
  CONSTRAINT `bl_purchase_ibfk_4` FOREIGN KEY (`metode_pembayaran`) REFERENCES `bl_rekening` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4351 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_purchase_bar`
--

DROP TABLE IF EXISTS `bl_purchase_bar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_purchase_bar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `jenis_pengeluaran` int(11) NOT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `nama_bahan_baku` varchar(255) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `tipe_produksi_id` int(11) DEFAULT NULL,
  `merk` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `ukuran` decimal(10,2) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `pack` varchar(255) DEFAULT NULL,
  `harga_satuan` decimal(10,2) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `total_unit` decimal(10,2) DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL,
  `hpp` decimal(10,2) DEFAULT NULL,
  `metode_pembayaran` int(11) DEFAULT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `pengusul` varchar(255) DEFAULT NULL,
  `catatan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kategori_id` (`kategori_id`),
  KEY `tipe_produksi_id` (`tipe_produksi_id`),
  CONSTRAINT `bl_purchase_bar_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `bl_kategori` (`id`),
  CONSTRAINT `bl_purchase_bar_ibfk_2` FOREIGN KEY (`tipe_produksi_id`) REFERENCES `bl_tipe_produksi` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_purchase_kitchen`
--

DROP TABLE IF EXISTS `bl_purchase_kitchen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_purchase_kitchen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `jenis_pengeluaran` int(11) NOT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `nama_bahan_baku` varchar(255) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `tipe_produksi_id` int(11) DEFAULT NULL,
  `merk` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `ukuran` decimal(10,2) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `pack` varchar(255) DEFAULT NULL,
  `harga_satuan` decimal(10,2) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `total_unit` decimal(10,2) DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL,
  `hpp` decimal(10,2) DEFAULT NULL,
  `metode_pembayaran` int(11) DEFAULT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `pengusul` varchar(255) DEFAULT NULL,
  `catatan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kategori_id` (`kategori_id`),
  KEY `tipe_produksi_id` (`tipe_produksi_id`),
  CONSTRAINT `bl_purchase_kitchen_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `bl_kategori` (`id`),
  CONSTRAINT `bl_purchase_kitchen_ibfk_2` FOREIGN KEY (`tipe_produksi_id`) REFERENCES `bl_tipe_produksi` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_purchase_pending`
--

DROP TABLE IF EXISTS `bl_purchase_pending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_purchase_pending` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal_pembelian` date NOT NULL,
  `jenis_pengeluaran` int(11) NOT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `nama_bahan_baku` varchar(255) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `tipe_produksi_id` int(11) DEFAULT NULL,
  `merk` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `ukuran` decimal(10,2) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `pack` varchar(255) DEFAULT NULL,
  `harga_satuan` decimal(10,2) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `total_unit` decimal(10,2) DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL,
  `hpp` decimal(10,2) DEFAULT NULL,
  `metode_pembayaran` int(11) DEFAULT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `pengusul` varchar(255) DEFAULT NULL,
  `catatan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kategori_id` (`kategori_id`),
  KEY `tipe_produksi_id` (`tipe_produksi_id`),
  CONSTRAINT `bl_purchase_pending_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `bl_kategori` (`id`),
  CONSTRAINT `bl_purchase_pending_ibfk_2` FOREIGN KEY (`tipe_produksi_id`) REFERENCES `bl_tipe_produksi` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_refund`
--

DROP TABLE IF EXISTS `bl_refund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `nilai` decimal(15,2) NOT NULL,
  `rekening` int(11) NOT NULL,
  `keterangan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `rekening` (`rekening`),
  CONSTRAINT `bl_refund_ibfk_1` FOREIGN KEY (`rekening`) REFERENCES `bl_rekening` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_rekap_rekening`
--

DROP TABLE IF EXISTS `bl_rekap_rekening`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_rekap_rekening` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `rekening_id` int(11) NOT NULL,
  `nilai` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tanggal_rekening` (`tanggal`,`rekening_id`),
  KEY `rekening_id` (`rekening_id`),
  CONSTRAINT `bl_rekap_rekening_ibfk_1` FOREIGN KEY (`rekening_id`) REFERENCES `bl_rekening` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=353197 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_rekening`
--

DROP TABLE IF EXISTS `bl_rekening`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_rekening` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_rekening` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_stok_opname`
--

DROP TABLE IF EXISTS `bl_stok_opname`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_stok_opname` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NOT NULL,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `merk` varchar(255) DEFAULT NULL,
  `ukuran` float NOT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `pack` varchar(50) DEFAULT NULL,
  `harga` float NOT NULL,
  `stok_awal` float NOT NULL,
  `stok_masuk` float NOT NULL,
  `stok_keluar` float NOT NULL,
  `stok_terbuang` float NOT NULL,
  `stok_penyesuaian` float NOT NULL,
  `stok_akhir` float NOT NULL,
  `unit_total` float NOT NULL,
  `nilai_total` float NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_purchase_date` (`bl_db_purchase_id`,`tanggal`)
) ENGINE=InnoDB AUTO_INCREMENT=1574 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_stok_opname_januari`
--

DROP TABLE IF EXISTS `bl_stok_opname_januari`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_stok_opname_januari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NOT NULL,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `merk` varchar(255) DEFAULT NULL,
  `ukuran` float NOT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `pack` varchar(50) DEFAULT NULL,
  `harga` float NOT NULL,
  `stok_awal` float NOT NULL,
  `stok_masuk` float NOT NULL,
  `stok_keluar` float NOT NULL,
  `stok_terbuang` float NOT NULL,
  `stok_penyesuaian` float NOT NULL,
  `stok_akhir` float NOT NULL,
  `unit_total` float NOT NULL,
  `nilai_total` float NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_purchase_date` (`bl_db_purchase_id`,`tanggal`)
) ENGINE=InnoDB AUTO_INCREMENT=242 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_stok_penyesuaian`
--

DROP TABLE IF EXISTS `bl_stok_penyesuaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_stok_penyesuaian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `alasan` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_db_purchase_id` (`bl_db_purchase_id`),
  CONSTRAINT `bl_stok_penyesuaian_ibfk_1` FOREIGN KEY (`bl_db_purchase_id`) REFERENCES `bl_db_purchase` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_stok_terbuang`
--

DROP TABLE IF EXISTS `bl_stok_terbuang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_stok_terbuang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `alasan` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_db_purchase_id` (`bl_db_purchase_id`),
  CONSTRAINT `bl_stok_terbuang_ibfk_1` FOREIGN KEY (`bl_db_purchase_id`) REFERENCES `bl_db_purchase` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_store_request`
--

DROP TABLE IF EXISTS `bl_store_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_store_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `jenis_pengeluaran` varchar(255) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` float NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bl_db_purchase_id` (`bl_db_purchase_id`),
  CONSTRAINT `bl_store_request_ibfk_1` FOREIGN KEY (`bl_db_purchase_id`) REFERENCES `bl_db_purchase` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1911 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_store_request_bar`
--

DROP TABLE IF EXISTS `bl_store_request_bar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_store_request_bar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `jenis_pengeluaran` varchar(50) NOT NULL DEFAULT 'BAR',
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `catatan` text,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_store_request_kitchen`
--

DROP TABLE IF EXISTS `bl_store_request_kitchen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_store_request_kitchen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `jenis_pengeluaran` varchar(50) NOT NULL DEFAULT 'BAR',
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `catatan` text,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bl_tipe_produksi`
--

DROP TABLE IF EXISTS `bl_tipe_produksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bl_tipe_produksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_tipe_produksi` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `generated_tabel`
--

DROP TABLE IF EXISTS `generated_tabel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `generated_tabel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tanggal` date NOT NULL,
  `kode_shift` varchar(50) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `status_masuk` enum('pending','sent') DEFAULT 'pending',
  `status_pulang` enum('pending','sent') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kode_user`
--

DROP TABLE IF EXISTS `kode_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kode_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_user` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_user` (`kode_user`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `member_news`
--

DROP TABLE IF EXISTS `member_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `urutan` int(11) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `member_promo`
--

DROP TABLE IF EXISTS `member_promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_promo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `urutan` int(11) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_base`
--

DROP TABLE IF EXISTS `pr_base`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_base` varchar(100) NOT NULL,
  `deskripsi` text,
  `satuan` varchar(20) DEFAULT NULL,
  `hpp` decimal(12,2) DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_customer`
--

DROP TABLE IF EXISTS `pr_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `kode_pelanggan` varchar(20) NOT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `telepon` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `level` varchar(20) DEFAULT 'Silver',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `kode_pelanggan` (`kode_pelanggan`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_customer_poin`
--

DROP TABLE IF EXISTS `pr_customer_poin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_customer_poin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `transaksi_id` int(11) DEFAULT NULL,
  `jumlah_poin` int(11) NOT NULL,
  `jenis` enum('per_produk','per_pembelian','penukaran') DEFAULT 'per_produk',
  `sumber` varchar(255) DEFAULT NULL,
  `tanggal_kedaluwarsa` date DEFAULT NULL,
  `status` enum('aktif','digunakan','kedaluwarsa') DEFAULT 'aktif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_customer_stamp`
--

DROP TABLE IF EXISTS `pr_customer_stamp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_customer_stamp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_transaksi_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `promo_stamp_id` int(11) DEFAULT NULL,
  `jumlah_stamp` int(11) DEFAULT '0',
  `last_stamp_at` datetime DEFAULT NULL,
  `masa_berlaku` date DEFAULT NULL,
  `status` enum('aktif','kadaluarsa','ditukar') DEFAULT 'aktif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_detail_extra`
--

DROP TABLE IF EXISTS `pr_detail_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_detail_extra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detail_transaksi_id` int(11) NOT NULL,
  `pr_produk_extra_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT '1',
  `harga` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `hpp` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('BERHASIL','BATAL','REFUND') DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `detail_transaksi_id` (`detail_transaksi_id`) USING BTREE,
  KEY `pr_produk_extra_id` (`pr_produk_extra_id`) USING BTREE,
  CONSTRAINT `pr_detail_extra_ibfk_1` FOREIGN KEY (`detail_transaksi_id`) REFERENCES `pr_detail_transaksi` (`id`),
  CONSTRAINT `pr_detail_extra_ibfk_2` FOREIGN KEY (`pr_produk_extra_id`) REFERENCES `pr_produk_extra` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_detail_transaksi`
--

DROP TABLE IF EXISTS `pr_detail_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_detail_transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_transaksi_id` int(11) NOT NULL,
  `pr_produk_id` int(11) NOT NULL,
  `detail_unit_id` varchar(64) DEFAULT NULL,
  `pr_detail_transaksi_paket_id` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `catatan` text,
  `is_printed` int(10) DEFAULT NULL,
  `status` enum('BERHASIL','BATAL','REFUND') DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_checked` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `pr_transaksi_id` (`pr_transaksi_id`) USING BTREE,
  KEY `pr_produk_id` (`pr_produk_id`) USING BTREE,
  CONSTRAINT `pr_detail_transaksi_ibfk_1` FOREIGN KEY (`pr_transaksi_id`) REFERENCES `pr_transaksi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pr_detail_transaksi_ibfk_2` FOREIGN KEY (`pr_produk_id`) REFERENCES `pr_produk` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_detail_transaksi_paket`
--

DROP TABLE IF EXISTS `pr_detail_transaksi_paket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_detail_transaksi_paket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_transaksi_id` int(11) NOT NULL,
  `pr_produk_paket_id` int(11) NOT NULL,
  `detail_unit_paket_id` varchar(100) DEFAULT NULL,
  `harga` int(11) NOT NULL DEFAULT '0',
  `jumlah` int(11) NOT NULL DEFAULT '1',
  `catatan` text,
  `status` enum('BERHASIL','BATAL','REFUND') DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_printed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_divisi`
--

DROP TABLE IF EXISTS `pr_divisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_divisi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(100) NOT NULL,
  `urutan_tampilan` int(3) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_extra`
--

DROP TABLE IF EXISTS `pr_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_extra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_extra` varchar(255) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `hpp` decimal(10,2) NOT NULL DEFAULT '0.00',
  `harga_jual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `sku` (`sku`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_jenis_order`
--

DROP TABLE IF EXISTS `pr_jenis_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_jenis_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_order` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `jenis_order` (`jenis_order`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_kasir_shift`
--

DROP TABLE IF EXISTS `pr_kasir_shift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_kasir_shift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kasir_id` int(11) NOT NULL,
  `modal_awal` decimal(15,2) NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  `total_penjualan` decimal(15,2) DEFAULT '0.00',
  `total_pending` decimal(15,2) DEFAULT '0.00',
  `modal_akhir` decimal(15,2) DEFAULT '0.00',
  `selisih` decimal(15,2) DEFAULT '0.00',
  `waktu_tutup` datetime DEFAULT NULL,
  `total_pendapatan` decimal(15,2) DEFAULT '0.00',
  `keterangan` text,
  `status` enum('OPEN','CLOSE') DEFAULT 'OPEN',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `transaksi_selesai` int(11) DEFAULT '0',
  `transaksi_pending` int(11) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_kasir_shift_log`
--

DROP TABLE IF EXISTS `pr_kasir_shift_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_kasir_shift_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_id` int(11) NOT NULL,
  `tipe` enum('penjualan','refund') NOT NULL,
  `metode_id` int(11) DEFAULT NULL,
  `rekening_id` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nominal` decimal(15,2) DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `shift_id` (`shift_id`),
  KEY `metode_id` (`metode_id`),
  KEY `rekening_id` (`rekening_id`),
  CONSTRAINT `pr_kasir_shift_log_ibfk_1` FOREIGN KEY (`shift_id`) REFERENCES `pr_kasir_shift` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pr_kasir_shift_log_ibfk_2` FOREIGN KEY (`metode_id`) REFERENCES `pr_metode_pembayaran` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pr_kasir_shift_log_ibfk_3` FOREIGN KEY (`rekening_id`) REFERENCES `bl_rekening` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_kategori`
--

DROP TABLE IF EXISTS `pr_kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `pr_divisi_id` int(11) DEFAULT NULL,
  `urutan` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `pr_divisi_id` (`pr_divisi_id`) USING BTREE,
  CONSTRAINT `pr_kategori_ibfk_1` FOREIGN KEY (`pr_divisi_id`) REFERENCES `pr_divisi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_log_stok_bahan_baku`
--

DROP TABLE IF EXISTS `pr_log_stok_bahan_baku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_log_stok_bahan_baku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bahan_id` int(11) NOT NULL,
  `divisi_id` int(11) NOT NULL,
  `jenis_transaksi` enum('purchase','store_request','penyesuaian','penjualan') NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `hpp` decimal(15,2) DEFAULT '0.00',
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `bahan_id` (`bahan_id`) USING BTREE,
  KEY `divisi_id` (`divisi_id`) USING BTREE,
  CONSTRAINT `pr_log_stok_bahan_baku_ibfk_1` FOREIGN KEY (`bahan_id`) REFERENCES `bl_db_belanja` (`id`),
  CONSTRAINT `pr_log_stok_bahan_baku_ibfk_2` FOREIGN KEY (`divisi_id`) REFERENCES `bl_divisi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=882 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_log_voucher`
--

DROP TABLE IF EXISTS `pr_log_voucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_log_voucher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `detail_transaksi_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `kode_voucher` varchar(50) NOT NULL,
  `jumlah_diskon` int(11) NOT NULL,
  `sisa_voucher` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `voucher_id` (`voucher_id`) USING BTREE,
  KEY `transaksi_id` (`transaksi_id`) USING BTREE,
  KEY `detail_transaksi_id` (`detail_transaksi_id`) USING BTREE,
  KEY `customer_id` (`customer_id`) USING BTREE,
  CONSTRAINT `pr_log_voucher_ibfk_1` FOREIGN KEY (`voucher_id`) REFERENCES `pr_voucher` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pr_log_voucher_ibfk_2` FOREIGN KEY (`transaksi_id`) REFERENCES `pr_transaksi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pr_log_voucher_ibfk_3` FOREIGN KEY (`detail_transaksi_id`) REFERENCES `pr_detail_transaksi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pr_log_voucher_ibfk_4` FOREIGN KEY (`customer_id`) REFERENCES `pr_customer` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_lokasi_printer`
--

DROP TABLE IF EXISTS `pr_lokasi_printer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_lokasi_printer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lokasi` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `nama_lokasi` (`nama_lokasi`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_meja`
--

DROP TABLE IF EXISTS `pr_meja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_meja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_meja` varchar(50) NOT NULL,
  `posisi_x` int(11) NOT NULL DEFAULT '0',
  `posisi_y` int(11) NOT NULL DEFAULT '0',
  `kapasitas` int(11) NOT NULL DEFAULT '1',
  `zona` enum('indoor','semi_indoor','teras','outdoor') NOT NULL,
  `bentuk` enum('persegi','panjang','panjang2','bulat') DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_meja_pembatas`
--

DROP TABLE IF EXISTS `pr_meja_pembatas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_meja_pembatas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zona` enum('indoor','semi_indoor','teras','outdoor') NOT NULL,
  `posisi_x` int(11) NOT NULL,
  `posisi_y` int(11) NOT NULL,
  `lebar` int(11) DEFAULT '100',
  `tinggi` int(11) DEFAULT '10',
  `orientasi` enum('horizontal','vertikal') DEFAULT 'horizontal',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_metode_pembayaran`
--

DROP TABLE IF EXISTS `pr_metode_pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_metode_pembayaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metode_pembayaran` varchar(50) NOT NULL,
  `bl_rekening_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `metode_pembayaran` (`metode_pembayaran`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_pembayaran`
--

DROP TABLE IF EXISTS `pr_pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_pembayaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaksi_id` int(11) DEFAULT NULL,
  `metode_id` int(11) DEFAULT NULL,
  `jumlah` decimal(12,0) DEFAULT NULL,
  `waktu_bayar` datetime DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `kasir_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `transaksi_id` (`transaksi_id`) USING BTREE,
  KEY `metode_id` (`metode_id`) USING BTREE,
  CONSTRAINT `pr_pembayaran_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `pr_transaksi` (`id`),
  CONSTRAINT `pr_pembayaran_ibfk_2` FOREIGN KEY (`metode_id`) REFERENCES `pr_metode_pembayaran` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_pengaturan`
--

DROP TABLE IF EXISTS `pr_pengaturan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_pengaturan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_outlet` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telepon` varchar(50) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `text_header` text,
  `text_footer` text,
  `show_header_text` tinyint(1) NOT NULL DEFAULT '1',
  `show_footer_text` tinyint(1) NOT NULL DEFAULT '1',
  `show_logo` tinyint(1) DEFAULT '1',
  `show_outlet` tinyint(1) DEFAULT '1',
  `show_address` tinyint(1) DEFAULT '1',
  `show_email` tinyint(1) DEFAULT '1',
  `show_phone` tinyint(1) DEFAULT '1',
  `show_invoice` tinyint(1) DEFAULT '1',
  `show_cashier_order` tinyint(1) DEFAULT '1',
  `show_order_time` tinyint(1) NOT NULL DEFAULT '1',
  `show_payment_time` tinyint(1) NOT NULL DEFAULT '1',
  `show_cashier_payment` tinyint(1) NOT NULL DEFAULT '1',
  `show_customer` tinyint(1) DEFAULT '1',
  `show_order_type` tinyint(1) NOT NULL DEFAULT '1',
  `show_table_number` tinyint(1) NOT NULL DEFAULT '1',
  `printer_name` varchar(255) DEFAULT NULL,
  `printer_type` enum('usb','bluetooth') DEFAULT 'usb',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_poin`
--

DROP TABLE IF EXISTS `pr_poin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_poin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_point` enum('per_produk','per_pembelian') NOT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `min_pembelian` decimal(10,2) DEFAULT NULL,
  `nilai_point` int(11) NOT NULL,
  `kedaluwarsa_hari` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_printer`
--

DROP TABLE IF EXISTS `pr_printer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_printer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lokasi_printer` varchar(50) DEFAULT NULL,
  `divisi` int(11) NOT NULL,
  `printer_name` varchar(100) NOT NULL,
  `port` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `python_port` int(11) DEFAULT '3000',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_printer_setting`
--

DROP TABLE IF EXISTS `pr_printer_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_printer_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `divisi_id` int(11) NOT NULL,
  `nama_outlet` varchar(255) DEFAULT NULL,
  `alamat` text,
  `kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `custom_header` text,
  `logo_url` text,
  `tampilkan_logo` enum('normal','penuh','tidak') DEFAULT 'normal',
  `tampilkan_kolom` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_produk`
--

DROP TABLE IF EXISTS `pr_produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(255) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `deskripsi` text,
  `kategori_id` int(11) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `hpp` decimal(10,2) DEFAULT NULL,
  `harga_jual` decimal(10,2) DEFAULT NULL,
  `monitor_persediaan` int(1) DEFAULT '1',
  `tampil` int(1) DEFAULT '1',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `kategori_id` (`kategori_id`) USING BTREE,
  CONSTRAINT `pr_produk_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `pr_kategori` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=270 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_produk_extra`
--

DROP TABLE IF EXISTS `pr_produk_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_produk_extra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) DEFAULT NULL,
  `nama_extra` varchar(100) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga` int(11) DEFAULT '0',
  `hpp` int(11) DEFAULT '0',
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_produk_paket`
--

DROP TABLE IF EXISTS `pr_produk_paket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_produk_paket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_paket` varchar(100) NOT NULL,
  `harga_paket` int(11) NOT NULL,
  `keterangan` text,
  `status` tinyint(1) DEFAULT '1',
  `divisi_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `divisi_id` (`divisi_id`),
  CONSTRAINT `pr_produk_paket_ibfk_1` FOREIGN KEY (`divisi_id`) REFERENCES `pr_divisi` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_produk_paket_detail`
--

DROP TABLE IF EXISTS `pr_produk_paket_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_produk_paket_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_produk_paket_id` int(11) NOT NULL,
  `pr_produk_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `paket_id` (`pr_produk_paket_id`),
  KEY `pr_produk_id` (`pr_produk_id`),
  CONSTRAINT `pr_produk_paket_detail_ibfk_1` FOREIGN KEY (`pr_produk_paket_id`) REFERENCES `pr_produk_paket` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pr_produk_paket_detail_ibfk_2` FOREIGN KEY (`pr_produk_id`) REFERENCES `pr_produk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_produksi_base`
--

DROP TABLE IF EXISTS `pr_produksi_base`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_produksi_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_base_id` int(11) DEFAULT NULL,
  `jumlah_dihasilkan` decimal(10,2) DEFAULT NULL,
  `divisi_id` int(11) DEFAULT NULL,
  `catatan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `pr_base_id` (`pr_base_id`) USING BTREE,
  KEY `divisi_id` (`divisi_id`) USING BTREE,
  CONSTRAINT `pr_produksi_base_ibfk_1` FOREIGN KEY (`pr_base_id`) REFERENCES `pr_base` (`id`),
  CONSTRAINT `pr_produksi_base_ibfk_2` FOREIGN KEY (`divisi_id`) REFERENCES `pr_divisi` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_promo_stamp`
--

DROP TABLE IF EXISTS `pr_promo_stamp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_promo_stamp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_promo` varchar(100) DEFAULT NULL,
  `deskripsi` text,
  `minimal_pembelian` int(11) DEFAULT '0',
  `berlaku_kelipatan` tinyint(1) DEFAULT '0',
  `produk_berlaku` int(11) DEFAULT NULL,
  `total_stamp_target` int(11) DEFAULT NULL,
  `hadiah` text,
  `masa_berlaku_hari` int(11) DEFAULT '30',
  `aktif` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_promo_voucher_auto`
--

DROP TABLE IF EXISTS `pr_promo_voucher_auto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_promo_voucher_auto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_promo` varchar(100) DEFAULT NULL,
  `tipe_trigger` enum('nominal','produk') NOT NULL DEFAULT 'nominal',
  `nilai` int(11) DEFAULT '0',
  `produk_trigger` int(11) DEFAULT NULL,
  `masa_berlaku` int(11) DEFAULT '30',
  `jenis` enum('persentase','nominal') NOT NULL,
  `nilai_voucher` int(11) NOT NULL,
  `min_pembelian` int(11) DEFAULT '0',
  `produk_id` int(11) DEFAULT NULL,
  `max_diskon` int(11) DEFAULT '0',
  `aktif` tinyint(1) DEFAULT '1',
  `maksimal_voucher` int(11) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_redeem_log`
--

DROP TABLE IF EXISTS `pr_redeem_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_redeem_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `redeem_setting_id` int(11) NOT NULL,
  `jenis` enum('poin','stamp') NOT NULL,
  `jumlah_digunakan` int(11) NOT NULL,
  `voucher_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_redeem_setting`
--

DROP TABLE IF EXISTS `pr_redeem_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_redeem_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_redeem` varchar(255) NOT NULL,
  `jenis` enum('poin','stamp') NOT NULL,
  `jumlah_dibutuhkan` int(11) NOT NULL,
  `jenis_voucher` enum('produk','diskon') NOT NULL,
  `tipe_diskon` enum('nominal','persentase') DEFAULT NULL,
  `nilai_voucher` int(11) DEFAULT NULL,
  `max_diskon` decimal(10,2) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `masa_berlaku` int(11) DEFAULT '30',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_refund`
--

DROP TABLE IF EXISTS `pr_refund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_refund` varchar(20) DEFAULT NULL,
  `pr_transaksi_id` int(11) NOT NULL,
  `no_transaksi` varchar(50) NOT NULL,
  `pr_detail_transaksi_id` int(11) DEFAULT NULL,
  `pr_produk_id` int(11) DEFAULT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `detail_extra_id` int(11) DEFAULT NULL,
  `produk_extra_id` int(11) DEFAULT NULL,
  `nama_extra` varchar(255) DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT '1',
  `harga` int(11) NOT NULL DEFAULT '0',
  `catatan` text,
  `alasan` text NOT NULL,
  `refund_by` varchar(100) NOT NULL,
  `metode_pembayaran_id` int(11) DEFAULT NULL,
  `waktu_refund` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_resep_base`
--

DROP TABLE IF EXISTS `pr_resep_base`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_resep_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_base_id` int(11) NOT NULL,
  `bahan_id` int(11) NOT NULL,
  `jumlah` decimal(10,2) DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `hpp` decimal(15,2) DEFAULT '0.00',
  `hpp_dinamis` decimal(15,2) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_pr_base` (`pr_base_id`) USING BTREE,
  KEY `fk_bahan_id` (`bahan_id`) USING BTREE,
  CONSTRAINT `fk_bahan_id` FOREIGN KEY (`bahan_id`) REFERENCES `bl_db_belanja` (`id`),
  CONSTRAINT `fk_pr_base` FOREIGN KEY (`pr_base_id`) REFERENCES `pr_base` (`id`),
  CONSTRAINT `pr_resep_base_ibfk_1` FOREIGN KEY (`pr_base_id`) REFERENCES `pr_base` (`id`),
  CONSTRAINT `pr_resep_base_ibfk_2` FOREIGN KEY (`bahan_id`) REFERENCES `bl_db_belanja` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_resep_produk`
--

DROP TABLE IF EXISTS `pr_resep_produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_resep_produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_produk_id` int(11) DEFAULT NULL,
  `jenis` enum('bahan_baku','base') NOT NULL,
  `bahan_id` int(11) DEFAULT NULL,
  `jumlah` decimal(10,2) DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `hpp` decimal(15,2) DEFAULT '0.00',
  `hpp_dinamis` decimal(15,2) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `pr_produk_id` (`pr_produk_id`) USING BTREE,
  CONSTRAINT `pr_resep_produk_ibfk_1` FOREIGN KEY (`pr_produk_id`) REFERENCES `pr_produk` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_reservasi`
--

DROP TABLE IF EXISTS `pr_reservasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_reservasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `nama_customer` varchar(100) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` time DEFAULT NULL,
  `jumlah_orang` int(11) DEFAULT NULL,
  `catatan` text,
  `total_bayar` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('menunggu','diterima','selesai','batal') DEFAULT 'menunggu',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_reservasi_detail`
--

DROP TABLE IF EXISTS `pr_reservasi_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_reservasi_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reservasi_id` int(11) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `catatan` text,
  `harga` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_reservasi_meja`
--

DROP TABLE IF EXISTS `pr_reservasi_meja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_reservasi_meja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reservasi_id` int(11) DEFAULT NULL,
  `meja_id` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `reservasi_id` (`reservasi_id`) USING BTREE,
  KEY `meja_id` (`meja_id`) USING BTREE,
  CONSTRAINT `pr_reservasi_meja_ibfk_1` FOREIGN KEY (`reservasi_id`) REFERENCES `pr_reservasi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pr_reservasi_meja_ibfk_2` FOREIGN KEY (`meja_id`) REFERENCES `pr_meja` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_stamp_log`
--

DROP TABLE IF EXISTS `pr_stamp_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_stamp_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `promo_stamp_id` int(11) DEFAULT NULL,
  `transaksi_id` int(11) DEFAULT NULL,
  `jenis` enum('tambah','tukar') DEFAULT 'tambah',
  `jumlah` int(11) DEFAULT NULL,
  `keterangan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_stok_bahan_baku`
--

DROP TABLE IF EXISTS `pr_stok_bahan_baku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_stok_bahan_baku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bahan_id` int(11) NOT NULL,
  `divisi_id` int(11) NOT NULL,
  `stok_awal` decimal(10,2) DEFAULT '0.00',
  `stok_masuk` decimal(10,2) DEFAULT '0.00',
  `stok_keluar` decimal(10,2) DEFAULT '0.00',
  `stok_penyesuaian` decimal(10,2) DEFAULT '0.00',
  `stok_sisa` decimal(10,2) DEFAULT '0.00',
  `hpp` decimal(15,2) DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `bahan_id` (`bahan_id`) USING BTREE,
  KEY `divisi_id` (`divisi_id`) USING BTREE,
  CONSTRAINT `pr_stok_bahan_baku_ibfk_1` FOREIGN KEY (`bahan_id`) REFERENCES `bl_db_belanja` (`id`),
  CONSTRAINT `pr_stok_bahan_baku_ibfk_2` FOREIGN KEY (`divisi_id`) REFERENCES `bl_divisi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_stok_base`
--

DROP TABLE IF EXISTS `pr_stok_base`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_stok_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_base_id` int(11) NOT NULL,
  `divisi_id` int(11) NOT NULL,
  `jumlah` decimal(12,2) DEFAULT '0.00',
  `satuan` varchar(20) DEFAULT NULL,
  `hpp` decimal(12,2) DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `pr_base_id` (`pr_base_id`) USING BTREE,
  KEY `divisi_id` (`divisi_id`) USING BTREE,
  CONSTRAINT `pr_stok_base_ibfk_1` FOREIGN KEY (`pr_base_id`) REFERENCES `pr_base` (`id`),
  CONSTRAINT `pr_stok_base_ibfk_2` FOREIGN KEY (`divisi_id`) REFERENCES `pr_divisi` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_struk`
--

DROP TABLE IF EXISTS `pr_struk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_struk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_outlet` varchar(100) DEFAULT NULL,
  `alamat` text,
  `email` varchar(100) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `custom_header` text,
  `custom_footer` text,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_struk_tampilan`
--

DROP TABLE IF EXISTS `pr_struk_tampilan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_struk_tampilan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `printer_id` int(11) DEFAULT NULL,
  `show_logo` tinyint(1) DEFAULT '1',
  `show_outlet` tinyint(1) DEFAULT '1',
  `show_alamat` tinyint(1) DEFAULT '1',
  `show_no_telepon` tinyint(1) DEFAULT '1',
  `show_custom_header` tinyint(1) DEFAULT '1',
  `show_invoice` tinyint(1) DEFAULT '1',
  `show_kasir_order` tinyint(1) DEFAULT '1',
  `show_kasir_bayar` tinyint(1) DEFAULT '1',
  `show_no_transaksi` tinyint(1) DEFAULT '1',
  `show_customer` tinyint(1) DEFAULT '1',
  `show_nomor_meja` tinyint(1) DEFAULT '1',
  `show_waktu_order` tinyint(1) DEFAULT '1',
  `show_waktu_bayar` tinyint(1) DEFAULT '1',
  `show_custom_footer` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_sync_log`
--

DROP TABLE IF EXISTS `pr_sync_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_sync_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) DEFAULT NULL,
  `id_table` int(11) DEFAULT NULL,
  `data` longtext,
  `status` enum('PENDING','FAILED','SENT') DEFAULT 'PENDING',
  `error_msg` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `sent_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_transaksi`
--

DROP TABLE IF EXISTS `pr_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `no_transaksi` varchar(20) NOT NULL,
  `waktu_order` datetime NOT NULL,
  `waktu_bayar` datetime DEFAULT NULL,
  `jenis_order_id` int(11) NOT NULL,
  `customer` varchar(100) DEFAULT NULL,
  `nomor_meja` varchar(100) DEFAULT NULL,
  `total_penjualan` decimal(12,2) NOT NULL,
  `kasir_order` int(11) NOT NULL,
  `kasir_bayar` int(11) DEFAULT NULL,
  `kode_voucher` varchar(50) DEFAULT NULL,
  `diskon` int(11) DEFAULT NULL,
  `total_pembayaran` int(11) DEFAULT NULL,
  `sisa_pembayaran` decimal(12,0) DEFAULT '0',
  `status_pembayaran` enum('BELUM_LUNAS','DP','LUNAS','BATAL','REFUND') DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `no_transaksi` (`no_transaksi`) USING BTREE,
  KEY `jenis_order_id` (`jenis_order_id`) USING BTREE,
  KEY `kasir_order` (`kasir_order`) USING BTREE,
  KEY `kasir_bayar` (`kasir_bayar`) USING BTREE,
  CONSTRAINT `pr_transaksi_ibfk_1` FOREIGN KEY (`jenis_order_id`) REFERENCES `pr_jenis_order` (`id`),
  CONSTRAINT `pr_transaksi_ibfk_3` FOREIGN KEY (`kasir_order`) REFERENCES `abs_pegawai` (`id`),
  CONSTRAINT `pr_transaksi_ibfk_4` FOREIGN KEY (`kasir_bayar`) REFERENCES `abs_pegawai` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_void`
--

DROP TABLE IF EXISTS `pr_void`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_void` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_void` varchar(50) DEFAULT NULL,
  `pr_transaksi_id` int(11) DEFAULT NULL,
  `no_transaksi` varchar(100) DEFAULT NULL,
  `detail_transaksi_id` int(11) DEFAULT NULL,
  `detail_transaksi_paket_id` int(11) DEFAULT NULL,
  `pr_produk_id` int(11) DEFAULT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `detail_extra_id` int(11) DEFAULT NULL,
  `produk_extra_id` int(11) DEFAULT NULL,
  `nama_extra` varchar(255) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `catatan` text,
  `alasan` text,
  `void_by` int(11) DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_printed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pr_voucher`
--

DROP TABLE IF EXISTS `pr_voucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_voucher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_voucher` varchar(50) NOT NULL,
  `jenis` enum('persentase','nominal','gratis_produk','cashback','min_pembelian') NOT NULL,
  `nilai` int(11) NOT NULL,
  `min_pembelian` int(11) DEFAULT '0',
  `produk_id` int(11) DEFAULT NULL,
  `jumlah_gratis` int(11) DEFAULT '0',
  `max_diskon` int(11) DEFAULT NULL,
  `maksimal_voucher` int(11) DEFAULT NULL,
  `sisa_voucher` int(11) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pr_transaksi_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `kode_voucher` (`kode_voucher`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `send_time` datetime NOT NULL,
  `status` enum('pending','sent') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `spbu`
--

DROP TABLE IF EXISTS `spbu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spbu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-30 15:33:58
