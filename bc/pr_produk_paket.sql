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

 Date: 17/05/2025 21:42:37
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_produk_paket
-- ----------------------------
DROP TABLE IF EXISTS `pr_produk_paket`;
CREATE TABLE `pr_produk_paket`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_paket` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `harga_paket` int(11) NOT NULL,
  `keterangan` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `status` tinyint(1) NULL DEFAULT 1,
  `divisi_id` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `divisi_id`(`divisi_id`) USING BTREE,
  CONSTRAINT `pr_produk_paket_ibfk_1` FOREIGN KEY (`divisi_id`) REFERENCES `pr_divisi` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
