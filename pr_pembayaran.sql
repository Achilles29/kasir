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

 Date: 01/05/2025 15:37:46
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_pembayaran
-- ----------------------------
DROP TABLE IF EXISTS `pr_pembayaran`;
CREATE TABLE `pr_pembayaran`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaksi_id` int(11) NULL DEFAULT NULL,
  `metode_id` int(11) NULL DEFAULT NULL,
  `jumlah` decimal(12, 0) NULL DEFAULT NULL,
  `waktu_bayar` datetime(0) NULL DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `kasir_id` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  `updated_at` datetime(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `transaksi_id`(`transaksi_id`) USING BTREE,
  INDEX `metode_id`(`metode_id`) USING BTREE,
  CONSTRAINT `pr_pembayaran_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `pr_transaksi` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `pr_pembayaran_ibfk_2` FOREIGN KEY (`metode_id`) REFERENCES `pr_metode_pembayaran` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
