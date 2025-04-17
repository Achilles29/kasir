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

 Date: 17/04/2025 06:46:15
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_log_voucher
-- ----------------------------
DROP TABLE IF EXISTS `pr_log_voucher`;
CREATE TABLE `pr_log_voucher`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `detail_transaksi_id` int(11) NULL DEFAULT NULL,
  `customer_id` int(11) NULL DEFAULT NULL,
  `kode_voucher` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_diskon` int(11) NOT NULL,
  `sisa_voucher` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `voucher_id`(`voucher_id`) USING BTREE,
  INDEX `transaksi_id`(`transaksi_id`) USING BTREE,
  INDEX `detail_transaksi_id`(`detail_transaksi_id`) USING BTREE,
  INDEX `customer_id`(`customer_id`) USING BTREE,
  CONSTRAINT `pr_log_voucher_ibfk_1` FOREIGN KEY (`voucher_id`) REFERENCES `pr_voucher` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `pr_log_voucher_ibfk_2` FOREIGN KEY (`transaksi_id`) REFERENCES `pr_transaksi` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `pr_log_voucher_ibfk_3` FOREIGN KEY (`detail_transaksi_id`) REFERENCES `pr_detail_transaksi` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `pr_log_voucher_ibfk_4` FOREIGN KEY (`customer_id`) REFERENCES `pr_customer` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pr_log_voucher
-- ----------------------------
INSERT INTO `pr_log_voucher` VALUES (1, 5, 5, NULL, 8, 'BEBEK10', 0, 100, '2025-04-17 01:09:33');
INSERT INTO `pr_log_voucher` VALUES (2, 5, 5, NULL, 8, 'BEBEK10', 6000, 0, '2025-04-17 01:17:42');
INSERT INTO `pr_log_voucher` VALUES (3, 5, 7, NULL, 10, 'BEBEK10', 6000, 99, '2025-04-17 01:30:41');

SET FOREIGN_KEY_CHECKS = 1;
