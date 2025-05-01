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

 Date: 01/05/2025 16:12:37
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bl_persediaan_awal
-- ----------------------------
DROP TABLE IF EXISTS `bl_persediaan_awal`;
CREATE TABLE `bl_persediaan_awal`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `bl_db_belanja_id` int(11) NOT NULL,
  `bl_db_purchase_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `total_unit` decimal(10, 2) NOT NULL,
  `total_harga` decimal(15, 2) NOT NULL,
  `hpp` decimal(15, 2) NOT NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `bl_db_belanja_id`(`bl_db_belanja_id`) USING BTREE,
  INDEX `bl_db_purchase_id`(`bl_db_purchase_id`) USING BTREE,
  CONSTRAINT `bl_persediaan_awal_ibfk_1` FOREIGN KEY (`bl_db_belanja_id`) REFERENCES `bl_db_belanja` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `bl_persediaan_awal_ibfk_2` FOREIGN KEY (`bl_db_purchase_id`) REFERENCES `bl_db_purchase` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
