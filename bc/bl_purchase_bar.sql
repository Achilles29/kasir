/*
 Navicat Premium Data Transfer

 Source Server         : VPS
 Source Server Type    : MySQL
 Source Server Version : 50740
 Source Host           : localhost:3306
 Source Schema         : namua

 Target Server Type    : MySQL
 Target Server Version : 50740
 File Encoding         : 65001

 Date: 01/05/2025 16:10:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bl_purchase_bar
-- ----------------------------
DROP TABLE IF EXISTS `bl_purchase_bar`;
CREATE TABLE `bl_purchase_bar`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `jenis_pengeluaran` int(11) NOT NULL,
  `nama_barang` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `nama_bahan_baku` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `kategori_id` int(11) NULL DEFAULT NULL,
  `tipe_produksi_id` int(11) NULL DEFAULT NULL,
  `merk` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `ukuran` decimal(10, 2) NULL DEFAULT NULL,
  `unit` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pack` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `harga_satuan` decimal(10, 2) NULL DEFAULT NULL,
  `kuantitas` int(11) NULL DEFAULT NULL,
  `total_unit` decimal(10, 2) NULL DEFAULT NULL,
  `total_harga` decimal(10, 2) NULL DEFAULT NULL,
  `hpp` decimal(10, 2) NULL DEFAULT NULL,
  `metode_pembayaran` int(11) NULL DEFAULT NULL,
  `status` enum('pending','verified','rejected') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'pending',
  `pengusul` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `catatan` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `kategori_id`(`kategori_id`) USING BTREE,
  INDEX `tipe_produksi_id`(`tipe_produksi_id`) USING BTREE,
  CONSTRAINT `bl_purchase_bar_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `bl_kategori` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `bl_purchase_bar_ibfk_2` FOREIGN KEY (`tipe_produksi_id`) REFERENCES `bl_tipe_produksi` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
