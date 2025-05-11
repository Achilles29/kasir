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

 Date: 11/05/2025 14:53:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_promo_stamp
-- ----------------------------
DROP TABLE IF EXISTS `pr_promo_stamp`;
CREATE TABLE `pr_promo_stamp`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_promo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `minimal_pembelian` int(11) NULL DEFAULT 0,
  `berlaku_kelipatan` tinyint(1) NULL DEFAULT 0,
  `produk_berlaku` int(11) NULL DEFAULT NULL,
  `total_stamp_target` int(11) NULL DEFAULT NULL,
  `hadiah` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `masa_berlaku_hari` int(11) NULL DEFAULT 30,
  `aktif` tinyint(1) NULL DEFAULT 1,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  `updated_at` datetime(0) NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
