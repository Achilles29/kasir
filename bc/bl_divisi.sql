/*
 Navicat Premium Data Transfer

 Source Server         : VPS
 Source Server Type    : MySQL
 Source Server Version : 50740
 Source Host           : localhost:3306
 Source Schema         : kasir

 Target Server Type    : MySQL
 Target Server Version : 50740
 File Encoding         : 65001

 Date: 30/05/2025 20:38:20
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bl_divisi
-- ----------------------------
DROP TABLE IF EXISTS `bl_divisi`;
CREATE TABLE `bl_divisi`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bl_divisi
-- ----------------------------
INSERT INTO `bl_divisi` VALUES (1, 'BEVERAGE', '2025-03-24 06:17:56', '2025-03-24 06:17:56');
INSERT INTO `bl_divisi` VALUES (2, 'FOOD', '2025-03-24 06:17:56', '2025-03-24 06:17:56');
INSERT INTO `bl_divisi` VALUES (3, 'EVENT', '2025-04-06 22:00:45', '2025-04-06 22:00:45');

SET FOREIGN_KEY_CHECKS = 1;
