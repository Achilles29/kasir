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

 Date: 17/04/2025 06:48:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_detail_transaksi
-- ----------------------------
DROP TABLE IF EXISTS `pr_detail_transaksi`;
CREATE TABLE `pr_detail_transaksi`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_transaksi_id` int(11) NOT NULL,
  `pr_produk_id` int(11) NOT NULL,
  `detail_unit_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(12, 2) NOT NULL,
  `catatan` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `is_printed` int(10) NULL DEFAULT NULL,
  `status` enum('BERHASIL','BATAL','REFUND') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  `updated_at` datetime(0) NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pr_transaksi_id`(`pr_transaksi_id`) USING BTREE,
  INDEX `pr_produk_id`(`pr_produk_id`) USING BTREE,
  CONSTRAINT `pr_detail_transaksi_ibfk_1` FOREIGN KEY (`pr_transaksi_id`) REFERENCES `pr_transaksi` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `pr_detail_transaksi_ibfk_2` FOREIGN KEY (`pr_produk_id`) REFERENCES `pr_produk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 125 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pr_detail_transaksi
-- ----------------------------
INSERT INTO `pr_detail_transaksi` VALUES (1, 1, 25, '67ff59aae2dc0', 1, 60000.00, '', 1, 'BERHASIL', '2025-04-16 09:18:02', '2025-04-16 20:27:33');
INSERT INTO `pr_detail_transaksi` VALUES (2, 1, 25, '67ff59aae2f45', 1, 60000.00, '', 1, 'BERHASIL', '2025-04-16 09:18:02', '2025-04-16 20:27:36');
INSERT INTO `pr_detail_transaksi` VALUES (3, 1, 25, '67ff59aae2f45', 1, 60000.00, '', 1, 'BERHASIL', '2025-04-16 09:18:02', '2025-04-16 20:27:36');
INSERT INTO `pr_detail_transaksi` VALUES (4, 1, 6, '67ff59aae427e', 1, 16000.00, 'tanpa gula', 1, 'BERHASIL', '2025-04-16 09:18:02', '2025-04-16 20:27:36');
INSERT INTO `pr_detail_transaksi` VALUES (5, 2, 25, '67ffb33ed68f2', 1, 60000.00, '', 1, 'BERHASIL', '2025-04-16 15:40:14', '2025-04-16 21:09:48');
INSERT INTO `pr_detail_transaksi` VALUES (6, 2, 25, '67ffb33ed7b25', 1, 60000.00, '', 1, 'BERHASIL', '2025-04-16 15:40:14', '2025-04-16 21:09:48');
INSERT INTO `pr_detail_transaksi` VALUES (7, 2, 25, '67ffb33ed7b25', 1, 60000.00, '', 1, 'BERHASIL', '2025-04-16 15:40:14', '2025-04-16 21:09:48');
INSERT INTO `pr_detail_transaksi` VALUES (8, 2, 12, '67ffb33ed91b2', 1, 20000.00, '', 1, 'BERHASIL', '2025-04-16 15:40:14', '2025-04-16 21:09:48');
INSERT INTO `pr_detail_transaksi` VALUES (9, 3, 25, '67ffbc3c79cab', 1, 60000.00, '', 0, 'BERHASIL', '2025-04-16 16:18:36', '2025-04-16 21:24:44');
INSERT INTO `pr_detail_transaksi` VALUES (10, 3, 26, '67ffbc3c79f14', 1, 40000.00, '', 0, 'BERHASIL', '2025-04-16 16:18:36', '2025-04-16 21:24:44');
INSERT INTO `pr_detail_transaksi` VALUES (11, 3, 7, '67ffbc3c7a033', 1, 17000.00, '', 0, 'BERHASIL', '2025-04-16 16:18:36', '2025-04-16 21:24:44');
INSERT INTO `pr_detail_transaksi` VALUES (12, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (13, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (14, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (15, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (16, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (17, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (18, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (19, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (20, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (21, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (22, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (23, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (24, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (25, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (26, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (27, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (28, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (29, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (30, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (31, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (32, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (33, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (34, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (35, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (36, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (37, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (38, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (39, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (40, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (41, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (42, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (43, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (44, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (45, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (46, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (47, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (48, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (49, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (50, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (51, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (52, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (53, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (54, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (55, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (56, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (57, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (58, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (59, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (60, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (61, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (62, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (63, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (64, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (65, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (66, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (67, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (68, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (69, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (70, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (71, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (72, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (73, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (74, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (75, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (76, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (77, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (78, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (79, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (80, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (81, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (82, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (83, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (84, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (85, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (86, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (87, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (88, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (89, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (90, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (91, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (92, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (93, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (94, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (95, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (96, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (97, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (98, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (99, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (100, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (101, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (102, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (103, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (104, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (105, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (106, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (107, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (108, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (109, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (110, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (111, 4, 6, '67ffbe9b18083', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (112, 4, 7, '67ffbe9b1f42f', 1, 17000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (113, 4, 7, '67ffbe9b1f51a', 1, 17000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (114, 4, 7, '67ffbe9b1f68d', 1, 17000.00, '', 0, 'BERHASIL', '2025-04-16 16:28:43', '2025-04-16 21:29:57');
INSERT INTO `pr_detail_transaksi` VALUES (116, 5, 6, '68003589552cb', 1, 16000.00, '', 1, 'BERHASIL', '2025-04-17 00:56:09', '2025-04-17 06:17:42');
INSERT INTO `pr_detail_transaksi` VALUES (117, 5, 6, '68003589552cb', 1, 16000.00, '', 1, 'BERHASIL', '2025-04-17 00:56:09', '2025-04-17 06:17:42');
INSERT INTO `pr_detail_transaksi` VALUES (118, 5, 25, '680035895688c', 1, 60000.00, '', 1, 'BERHASIL', '2025-04-17 00:56:09', '2025-04-17 06:17:42');
INSERT INTO `pr_detail_transaksi` VALUES (119, 5, 7, '6800358956951', 1, 17000.00, '', 1, 'BERHASIL', '2025-04-17 00:56:09', '2025-04-17 06:17:42');
INSERT INTO `pr_detail_transaksi` VALUES (120, 6, 6, '68003acc4b72e', 1, 16000.00, '', 0, 'BERHASIL', '2025-04-17 01:18:36', '2025-04-17 06:21:26');
INSERT INTO `pr_detail_transaksi` VALUES (121, 7, 25, '68003bc76d458', 1, 60000.00, '', 1, 'BERHASIL', '2025-04-17 01:22:47', '2025-04-17 06:30:41');
INSERT INTO `pr_detail_transaksi` VALUES (122, 7, 25, '68003bc76e1a3', 1, 60000.00, '', 1, 'BERHASIL', '2025-04-17 01:22:47', '2025-04-17 06:30:41');
INSERT INTO `pr_detail_transaksi` VALUES (123, 7, 7, '68003bc76e27e', 1, 17000.00, '', 1, 'BERHASIL', '2025-04-17 01:22:47', '2025-04-17 06:30:41');
INSERT INTO `pr_detail_transaksi` VALUES (124, 7, 50, '68003bc76e30b', 1, 19000.00, '', 1, 'BERHASIL', '2025-04-17 01:22:47', '2025-04-17 06:30:41');

SET FOREIGN_KEY_CHECKS = 1;
