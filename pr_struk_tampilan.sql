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

 Date: 17/04/2025 06:50:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_struk_tampilan
-- ----------------------------
DROP TABLE IF EXISTS `pr_struk_tampilan`;
CREATE TABLE `pr_struk_tampilan`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `printer_id` int(11) NULL DEFAULT NULL,
  `show_logo` tinyint(1) NULL DEFAULT 1,
  `show_outlet` tinyint(1) NULL DEFAULT 1,
  `show_alamat` tinyint(1) NULL DEFAULT 1,
  `show_no_telepon` tinyint(1) NULL DEFAULT 1,
  `show_custom_header` tinyint(1) NULL DEFAULT 1,
  `show_invoice` tinyint(1) NULL DEFAULT 1,
  `show_kasir_order` tinyint(1) NULL DEFAULT 1,
  `show_kasir_bayar` tinyint(1) NULL DEFAULT 1,
  `show_no_transaksi` tinyint(1) NULL DEFAULT 1,
  `show_customer` tinyint(1) NULL DEFAULT 1,
  `show_nomor_meja` tinyint(1) NULL DEFAULT 1,
  `show_waktu_order` tinyint(1) NULL DEFAULT 1,
  `show_waktu_bayar` tinyint(1) NULL DEFAULT 1,
  `show_custom_footer` tinyint(1) NULL DEFAULT 1,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  `updated_at` datetime(0) NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pr_struk_tampilan
-- ----------------------------
INSERT INTO `pr_struk_tampilan` VALUES (1, 1, 0, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, '2025-04-12 13:39:14', '2025-04-13 11:24:02');
INSERT INTO `pr_struk_tampilan` VALUES (2, 2, 0, 0, 0, 0, 0, 0, 1, 0, 1, 1, 1, 1, 0, 0, '2025-04-12 13:39:14', '2025-04-13 09:35:39');
INSERT INTO `pr_struk_tampilan` VALUES (3, 3, 0, 0, 0, 0, 0, 0, 1, 0, 1, 1, 1, 1, 0, 0, '2025-04-12 13:39:14', '2025-04-13 11:14:19');
INSERT INTO `pr_struk_tampilan` VALUES (4, 4, 0, 0, 0, 0, 0, 0, 1, 0, 1, 1, 1, 1, 0, 0, '2025-04-12 13:39:14', '2025-04-13 11:17:40');

SET FOREIGN_KEY_CHECKS = 1;
