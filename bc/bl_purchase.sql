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

 Date: 01/05/2025 16:10:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bl_purchase
-- ----------------------------
DROP TABLE IF EXISTS `bl_purchase`;
CREATE TABLE `bl_purchase`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `jenis_pengeluaran` int(11) NOT NULL,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `total_unit` float NOT NULL,
  `total_harga` decimal(10, 2) NOT NULL,
  `hpp` decimal(10, 2) NOT NULL,
  `status` enum('pending','verified','rejected') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'pending',
  `pengusul` enum('purchase','bar','kitchen') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `metode_pembayaran` int(11) NOT NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jenis_pengeluaran`(`jenis_pengeluaran`) USING BTREE,
  INDEX `bl_db_belanja_id`(`bl_db_belanja_id`) USING BTREE,
  INDEX `bl_db_purchase_id`(`bl_db_purchase_id`) USING BTREE,
  INDEX `metode_pembayaran`(`metode_pembayaran`) USING BTREE,
  CONSTRAINT `bl_purchase_ibfk_1` FOREIGN KEY (`jenis_pengeluaran`) REFERENCES `bl_jenis_pengeluaran` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `bl_purchase_ibfk_2` FOREIGN KEY (`bl_db_belanja_id`) REFERENCES `bl_db_belanja` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `bl_purchase_ibfk_3` FOREIGN KEY (`bl_db_purchase_id`) REFERENCES `bl_db_purchase` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `bl_purchase_ibfk_4` FOREIGN KEY (`metode_pembayaran`) REFERENCES `bl_rekening` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3661 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
