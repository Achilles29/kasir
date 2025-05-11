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

 Date: 11/05/2025 15:55:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_customer_stamp
-- ----------------------------
DROP TABLE IF EXISTS `pr_customer_stamp`;
CREATE TABLE `pr_customer_stamp`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_transaksi_id` int(11) NULL DEFAULT NULL,
  `customer_id` int(11) NULL DEFAULT NULL,
  `promo_stamp_id` int(11) NULL DEFAULT NULL,
  `jumlah_stamp` int(11) NULL DEFAULT 0,
  `last_stamp_at` datetime(0) NULL DEFAULT NULL,
  `masa_berlaku` date NULL DEFAULT NULL,
  `status` enum('aktif','kadaluarsa','ditukar') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'aktif',
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  `updated_at` datetime(0) NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
