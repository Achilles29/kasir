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

 Date: 17/04/2025 06:49:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_poin
-- ----------------------------
DROP TABLE IF EXISTS `pr_poin`;
CREATE TABLE `pr_poin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_point` enum('per_produk','per_pembelian') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `produk_id` int(11) NULL DEFAULT NULL,
  `min_pembelian` decimal(10, 2) NULL DEFAULT NULL,
  `nilai_point` int(11) NOT NULL,
  `kedaluwarsa_hari` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  `updated_at` datetime(0) NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pr_poin
-- ----------------------------
INSERT INTO `pr_poin` VALUES (3, 'per_produk', 242, 0.00, 2, 365, '2025-03-24 06:18:05', '2025-04-16 20:50:32');
INSERT INTO `pr_poin` VALUES (4, 'per_pembelian', NULL, 100000.00, 1, 365, '2025-03-24 06:18:05', '2025-04-16 20:50:37');
INSERT INTO `pr_poin` VALUES (7, 'per_produk', 235, 0.00, 5, 365, '2025-03-24 06:18:05', '2025-04-16 20:50:37');
INSERT INTO `pr_poin` VALUES (10, 'per_produk', 23, 0.00, 2, 365, '2025-03-24 06:18:05', '2025-04-16 20:50:37');
INSERT INTO `pr_poin` VALUES (11, 'per_produk', 25, 0.00, 3, 365, '2025-03-24 06:18:05', '2025-04-16 20:50:37');
INSERT INTO `pr_poin` VALUES (12, 'per_produk', 13, 0.00, 2, 365, '2025-03-24 06:18:05', '2025-04-16 20:50:37');

SET FOREIGN_KEY_CHECKS = 1;
