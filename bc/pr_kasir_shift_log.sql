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

 Date: 13/05/2025 19:54:51
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_kasir_shift_log
-- ----------------------------
DROP TABLE IF EXISTS `pr_kasir_shift_log`;
CREATE TABLE `pr_kasir_shift_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_id` int(11) NOT NULL,
  `tipe` enum('penjualan','refund') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `metode_id` int(11) NULL DEFAULT NULL,
  `rekening_id` int(11) NULL DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nominal` decimal(15, 2) NULL DEFAULT 0,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `shift_id`(`shift_id`) USING BTREE,
  INDEX `metode_id`(`metode_id`) USING BTREE,
  INDEX `rekening_id`(`rekening_id`) USING BTREE,
  CONSTRAINT `pr_kasir_shift_log_ibfk_1` FOREIGN KEY (`shift_id`) REFERENCES `pr_kasir_shift` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `pr_kasir_shift_log_ibfk_2` FOREIGN KEY (`metode_id`) REFERENCES `pr_metode_pembayaran` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pr_kasir_shift_log_ibfk_3` FOREIGN KEY (`rekening_id`) REFERENCES `bl_rekening` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
