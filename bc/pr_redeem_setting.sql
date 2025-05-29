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

 Date: 26/05/2025 14:01:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_redeem_setting
-- ----------------------------
DROP TABLE IF EXISTS `pr_redeem_setting`;
CREATE TABLE `pr_redeem_setting`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_redeem` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `jenis` enum('poin','stamp') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `jumlah_dibutuhkan` int(11) NOT NULL,
  `jenis_voucher` enum('produk','diskon') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tipe_diskon` enum('nominal','persentase') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `nilai_voucher` int(11) NULL DEFAULT NULL,
  `max_diskon` decimal(10, 2) NULL DEFAULT NULL,
  `produk_id` int(11) NULL DEFAULT NULL,
  `masa_berlaku` int(11) NULL DEFAULT 30,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
