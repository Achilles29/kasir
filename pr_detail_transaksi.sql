/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100432
 Source Host           : localhost:3306
 Source Schema         : namua

 Target Server Type    : MySQL
 Target Server Version : 100432
 File Encoding         : 65001

 Date: 01/05/2025 15:37:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_detail_transaksi
-- ----------------------------
DROP TABLE IF EXISTS `pr_detail_transaksi`;
CREATE TABLE `pr_detail_transaksi`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_transaksi_id` int(11) NOT NULL,
  `pr_produk_id` int(11) NOT NULL,
  `detail_unit_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(12, 2) NOT NULL,
  `catatan` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `is_printed` int(10) NULL DEFAULT NULL,
  `status` enum('BERHASIL','BATAL','REFUND') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  `updated_at` datetime(0) NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `is_checked` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pr_transaksi_id`(`pr_transaksi_id`) USING BTREE,
  INDEX `pr_produk_id`(`pr_produk_id`) USING BTREE,
  CONSTRAINT `pr_detail_transaksi_ibfk_1` FOREIGN KEY (`pr_transaksi_id`) REFERENCES `pr_transaksi` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `pr_detail_transaksi_ibfk_2` FOREIGN KEY (`pr_produk_id`) REFERENCES `pr_produk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 154 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
