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

 Date: 01/05/2025 16:12:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bl_produk
-- ----------------------------
DROP TABLE IF EXISTS `bl_produk`;
CREATE TABLE `bl_produk`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `kategori` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `satuan` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `harga_jual` decimal(15, 2) NOT NULL,
  `sku` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `divisi_id` int(255) NOT NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 319 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
