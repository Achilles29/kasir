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

 Date: 01/05/2025 21:17:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_pembayaran
-- ----------------------------
DROP TABLE IF EXISTS `pr_pembayaran`;
CREATE TABLE `pr_pembayaran`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaksi_id` int(11) NULL DEFAULT NULL,
  `metode_id` int(11) NULL DEFAULT NULL,
  `jumlah` decimal(12, 0) NULL DEFAULT NULL,
  `waktu_bayar` datetime(0) NULL DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `kasir_id` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `transaksi_id`(`transaksi_id`) USING BTREE,
  INDEX `metode_id`(`metode_id`) USING BTREE,
  CONSTRAINT `pr_pembayaran_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `pr_transaksi` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `pr_pembayaran_ibfk_2` FOREIGN KEY (`metode_id`) REFERENCES `pr_metode_pembayaran` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pr_pembayaran
-- ----------------------------
INSERT INTO `pr_pembayaran` VALUES (7, 8, 8, 189000, '2025-05-01 17:19:15', '', 6, '2025-05-01 17:19:15', '2025-05-01 17:19:15');
INSERT INTO `pr_pembayaran` VALUES (8, 12, 1, 0, '2025-05-01 17:58:43', '', 6, '2025-05-01 17:58:43', '2025-05-01 17:58:43');
INSERT INTO `pr_pembayaran` VALUES (9, 12, 2, 18000, '2025-05-01 17:58:43', '', 6, '2025-05-01 17:58:43', '2025-05-01 17:58:43');
INSERT INTO `pr_pembayaran` VALUES (10, 13, 1, 0, '2025-05-01 18:08:55', '', 6, '2025-05-01 18:08:55', '2025-05-01 18:08:55');
INSERT INTO `pr_pembayaran` VALUES (11, 13, 2, 156000, '2025-05-01 18:08:55', '', 6, '2025-05-01 18:08:55', '2025-05-01 18:08:55');
INSERT INTO `pr_pembayaran` VALUES (12, 14, 1, 102000, '2025-05-01 18:12:00', '', 6, '2025-05-01 18:12:00', '2025-05-01 18:12:00');
INSERT INTO `pr_pembayaran` VALUES (13, 15, 1, 200000, '2025-05-01 18:48:52', '', 6, '2025-05-01 18:48:52', '2025-05-01 18:48:52');
INSERT INTO `pr_pembayaran` VALUES (14, 16, 1, 201000, '2025-05-01 19:02:26', '', 6, '2025-05-01 19:02:26', '2025-05-01 19:02:26');
INSERT INTO `pr_pembayaran` VALUES (15, 17, 1, 50000, '2025-05-01 19:11:39', '', 6, '2025-05-01 19:11:39', '2025-05-01 19:11:39');
INSERT INTO `pr_pembayaran` VALUES (16, 18, 1, 0, '2025-05-01 19:18:44', '', 6, '2025-05-01 19:18:44', '2025-05-01 19:18:44');
INSERT INTO `pr_pembayaran` VALUES (17, 18, 2, 79000, '2025-05-01 19:18:44', '', 6, '2025-05-01 19:18:44', '2025-05-01 19:18:44');
INSERT INTO `pr_pembayaran` VALUES (18, 19, 1, 100000, '2025-05-01 19:44:09', '', 6, '2025-05-01 19:44:09', '2025-05-01 19:44:09');
INSERT INTO `pr_pembayaran` VALUES (19, 20, 1, 0, '2025-05-01 21:11:01', '', 6, '2025-05-01 21:11:01', '2025-05-01 21:11:01');
INSERT INTO `pr_pembayaran` VALUES (20, 20, 2, 44000, '2025-05-01 21:11:01', '', 6, '2025-05-01 21:11:01', '2025-05-01 21:11:01');

SET FOREIGN_KEY_CHECKS = 1;
