/*
 Navicat Premium Data Transfer

 Source Server         : namua
 Source Server Type    : MySQL
 Source Server Version : 100432
 Source Host           : localhost:3306
 Source Schema         : namua

 Target Server Type    : MySQL
 Target Server Version : 100432
 File Encoding         : 65001

 Date: 11/05/2025 15:29:20
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_stamp_log
-- ----------------------------
DROP TABLE IF EXISTS `pr_stamp_log`;
CREATE TABLE `pr_stamp_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NULL DEFAULT NULL,
  `promo_stamp_id` int(11) NULL DEFAULT NULL,
  `transaksi_id` int(11) NULL DEFAULT NULL,
  `jenis` enum('tambah','tukar') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'tambah',
  `jumlah` int(11) NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  `updated_at_at` datetime(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
