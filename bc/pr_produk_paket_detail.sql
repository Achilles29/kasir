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

 Date: 17/05/2025 21:42:46
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_produk_paket_detail
-- ----------------------------
DROP TABLE IF EXISTS `pr_produk_paket_detail`;
CREATE TABLE `pr_produk_paket_detail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paket_id` int(11) NOT NULL,
  `pr_produk_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `paket_id`(`paket_id`) USING BTREE,
  INDEX `pr_produk_id`(`pr_produk_id`) USING BTREE,
  CONSTRAINT `pr_produk_paket_detail_ibfk_1` FOREIGN KEY (`paket_id`) REFERENCES `pr_produk_paket` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `pr_produk_paket_detail_ibfk_2` FOREIGN KEY (`pr_produk_id`) REFERENCES `pr_produk` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
