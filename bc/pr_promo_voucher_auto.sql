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

 Date: 14/05/2025 20:31:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_promo_voucher_auto
-- ----------------------------
DROP TABLE IF EXISTS `pr_promo_voucher_auto`;
CREATE TABLE `pr_promo_voucher_auto`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_promo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tipe_trigger` enum('nominal','produk') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'nominal',
  `nilai` int(11) NULL DEFAULT 0,
  `produk_trigger` int(11) NULL DEFAULT NULL,
  `masa_berlaku` int(11) NULL DEFAULT 30,
  `jenis` enum('persentase','nominal') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_voucher` int(11) NOT NULL,
  `min_pembelian` int(11) NULL DEFAULT 0,
  `produk_id` int(11) NULL DEFAULT NULL,
  `max_diskon` int(11) NULL DEFAULT 0,
  `aktif` tinyint(1) NULL DEFAULT 1,
  `maksimal_voucher` int(11) NULL DEFAULT 1,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  `updated_at` datetime(0) NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
