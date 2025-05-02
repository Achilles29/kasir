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

 Date: 01/05/2025 21:17:42
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
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `is_checked` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pr_transaksi_id`(`pr_transaksi_id`) USING BTREE,
  INDEX `pr_produk_id`(`pr_produk_id`) USING BTREE,
  CONSTRAINT `pr_detail_transaksi_ibfk_1` FOREIGN KEY (`pr_transaksi_id`) REFERENCES `pr_transaksi` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `pr_detail_transaksi_ibfk_2` FOREIGN KEY (`pr_produk_id`) REFERENCES `pr_produk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 110 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pr_detail_transaksi
-- ----------------------------
INSERT INTO `pr_detail_transaksi` VALUES (1, 1, 49, '6812f81e61788', 1, 20000.00, '', 1, 'BERHASIL', '2025-05-01 11:27:10', '2025-05-01 11:27:46', 0);
INSERT INTO `pr_detail_transaksi` VALUES (2, 1, 47, '6812f81e6193b', 1, 21000.00, '', 1, 'BERHASIL', '2025-05-01 11:27:10', '2025-05-01 11:27:46', 0);
INSERT INTO `pr_detail_transaksi` VALUES (3, 1, 83, '6812f81e61a9b', 1, 15000.00, '', 1, 'BERHASIL', '2025-05-01 11:27:10', '2025-05-01 11:27:46', 0);
INSERT INTO `pr_detail_transaksi` VALUES (4, 2, 83, '68130256e2ab9', 1, 15000.00, '', 1, 'BATAL', '2025-05-01 12:10:46', '2025-05-01 13:02:40', 0);
INSERT INTO `pr_detail_transaksi` VALUES (5, 2, 49, '6813061654050', 1, 20000.00, '', 1, 'BATAL', '2025-05-01 12:26:46', '2025-05-01 13:02:40', 0);
INSERT INTO `pr_detail_transaksi` VALUES (6, 2, 47, '68130616541aa', 1, 21000.00, '', 1, 'BATAL', '2025-05-01 12:26:46', '2025-05-01 13:02:40', 0);
INSERT INTO `pr_detail_transaksi` VALUES (7, 3, 216, '681307912ba4c', 1, 26000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (8, 3, 80, '681307912dc49', 1, 10000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (9, 3, 80, '681307912dc49', 1, 10000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (10, 3, 82, '681307912f285', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (11, 3, 82, '681307912f285', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (12, 3, 86, '681307912f512', 1, 17000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (13, 3, 86, '681307912f512', 1, 17000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (14, 3, 84, '681307912f6c9', 1, 16000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (15, 3, 155, '681307912f7c8', 1, 28000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (16, 3, 154, '681307912f8a1', 1, 25000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (17, 3, 145, '681307912f971', 1, 28000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (18, 3, 163, '681307912fa4b', 1, 35000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (19, 3, 163, '681307912fa4b', 1, 35000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (20, 3, 163, '681307912fa4b', 1, 35000.00, '', 1, 'BERHASIL', '2025-05-01 12:33:05', '2025-05-01 13:48:17', 0);
INSERT INTO `pr_detail_transaksi` VALUES (21, 4, 145, '68130831947af', 1, 28000.00, '', 1, 'BERHASIL', '2025-05-01 12:35:45', '2025-05-01 13:49:40', 0);
INSERT INTO `pr_detail_transaksi` VALUES (22, 5, 124, '681318aa329aa', 1, 4000.00, '', 1, 'BERHASIL', '2025-05-01 13:46:02', '2025-05-01 13:47:11', 0);
INSERT INTO `pr_detail_transaksi` VALUES (23, 5, 124, '681318aa3b23f', 1, 4000.00, '', 1, 'BERHASIL', '2025-05-01 13:46:02', '2025-05-01 13:47:11', 0);
INSERT INTO `pr_detail_transaksi` VALUES (24, 5, 75, '681318aa3c4c7', 1, 23000.00, '', 1, 'BERHASIL', '2025-05-01 13:46:02', '2025-05-01 13:47:11', 0);
INSERT INTO `pr_detail_transaksi` VALUES (25, 5, 56, '681318aa3c664', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 13:46:02', '2025-05-01 13:47:11', 0);
INSERT INTO `pr_detail_transaksi` VALUES (26, 5, 177, '681318aa3c7e3', 1, 25000.00, '', 1, 'BERHASIL', '2025-05-01 13:46:02', '2025-05-01 13:47:11', 0);
INSERT INTO `pr_detail_transaksi` VALUES (27, 5, 166, '681318aa3c953', 1, 32000.00, '', 1, 'BERHASIL', '2025-05-01 13:46:02', '2025-05-01 13:47:11', 0);
INSERT INTO `pr_detail_transaksi` VALUES (28, 6, 134, '681319d242e65', 1, 43000.00, '', 1, 'BERHASIL', '2025-05-01 13:50:58', '2025-05-01 13:51:30', 0);
INSERT INTO `pr_detail_transaksi` VALUES (29, 6, 86, '681319d245290', 1, 17000.00, '', 1, 'BERHASIL', '2025-05-01 13:50:58', '2025-05-01 13:51:30', 0);
INSERT INTO `pr_detail_transaksi` VALUES (30, 7, 241, '68131a4098cbe', 1, 1000.00, '', 1, 'BERHASIL', '2025-05-01 13:52:48', '2025-05-01 13:56:00', 0);
INSERT INTO `pr_detail_transaksi` VALUES (31, 7, 121, '68131a409b495', 1, 1500.00, '', 1, 'BERHASIL', '2025-05-01 13:52:48', '2025-05-01 13:56:00', 0);
INSERT INTO `pr_detail_transaksi` VALUES (32, 7, 153, '68131a409cb7c', 1, 68000.00, '', 1, 'BERHASIL', '2025-05-01 13:52:48', '2025-05-01 13:56:00', 0);
INSERT INTO `pr_detail_transaksi` VALUES (33, 7, 84, '68131a409ccfe', 1, 16000.00, '', 1, 'BERHASIL', '2025-05-01 13:52:48', '2025-05-01 13:56:00', 0);
INSERT INTO `pr_detail_transaksi` VALUES (34, 8, 43, '68131bcd73c1c', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 13:59:25', '2025-05-01 17:19:15', 0);
INSERT INTO `pr_detail_transaksi` VALUES (35, 8, 188, '68131bcd73e5f', 1, 22000.00, '', 1, 'BERHASIL', '2025-05-01 13:59:25', '2025-05-01 17:19:15', 0);
INSERT INTO `pr_detail_transaksi` VALUES (36, 8, 41, '68131bcd7c3c7', 1, 19000.00, '', 1, 'BERHASIL', '2025-05-01 13:59:25', '2025-05-01 17:19:15', 0);
INSERT INTO `pr_detail_transaksi` VALUES (37, 8, 173, '68131bcd7c667', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 13:59:25', '2025-05-01 17:19:15', 0);
INSERT INTO `pr_detail_transaksi` VALUES (38, 8, 182, '68131bcd7c830', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 13:59:25', '2025-05-01 17:19:15', 0);
INSERT INTO `pr_detail_transaksi` VALUES (39, 8, 25, '68131bcd7ca00', 1, 20000.00, 'whiskey', 1, 'BERHASIL', '2025-05-01 13:59:25', '2025-05-01 17:19:15', 0);
INSERT INTO `pr_detail_transaksi` VALUES (40, 9, 116, '68131c6e43490', 1, 20000.00, '', 1, 'BERHASIL', '2025-05-01 14:02:06', '2025-05-01 14:02:59', 0);
INSERT INTO `pr_detail_transaksi` VALUES (41, 9, 84, '68131c6e45a31', 1, 16000.00, '', 1, 'BERHASIL', '2025-05-01 14:02:06', '2025-05-01 14:02:59', 0);
INSERT INTO `pr_detail_transaksi` VALUES (42, 9, 6, '68131c6e45c49', 1, 21000.00, 'manis', 1, 'BERHASIL', '2025-05-01 14:02:06', '2025-05-01 14:02:59', 0);
INSERT INTO `pr_detail_transaksi` VALUES (43, 9, 215, '68131c6e45dfe', 1, 22000.00, '', 1, 'BERHASIL', '2025-05-01 14:02:06', '2025-05-01 14:02:59', 0);
INSERT INTO `pr_detail_transaksi` VALUES (44, 9, 144, '68131c6e45fef', 1, 58000.00, '', 1, 'BERHASIL', '2025-05-01 14:02:06', '2025-05-01 14:02:59', 0);
INSERT INTO `pr_detail_transaksi` VALUES (45, 9, 131, '68131c6e4614e', 1, 32000.00, '', 1, 'BERHASIL', '2025-05-01 14:02:06', '2025-05-01 14:02:59', 0);
INSERT INTO `pr_detail_transaksi` VALUES (46, 9, 179, '68131c6e462dd', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 14:02:06', '2025-05-01 14:02:59', 0);
INSERT INTO `pr_detail_transaksi` VALUES (47, 10, 203, '6813204b20bc0', 1, 18000.00, '', 1, NULL, '2025-05-01 14:18:35', '2025-05-01 14:18:43', 0);
INSERT INTO `pr_detail_transaksi` VALUES (48, 11, 57, '68132d2ce6076', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 15:13:32', '2025-05-01 15:13:58', 0);
INSERT INTO `pr_detail_transaksi` VALUES (49, 11, 53, '68132d2ce8420', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 15:13:32', '2025-05-01 15:13:58', 0);
INSERT INTO `pr_detail_transaksi` VALUES (50, 11, 186, '68132d2ce8623', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 15:13:32', '2025-05-01 15:13:58', 0);
INSERT INTO `pr_detail_transaksi` VALUES (51, 11, 216, '68132d2ce8792', 1, 26000.00, '', 1, 'BERHASIL', '2025-05-01 15:13:32', '2025-05-01 15:13:58', 0);
INSERT INTO `pr_detail_transaksi` VALUES (52, 8, 237, '681331e1bbd67', 1, 6000.00, '', 1, 'BATAL', '2025-05-01 15:33:37', '2025-05-01 17:10:29', 0);
INSERT INTO `pr_detail_transaksi` VALUES (53, 8, 28, '681331e1bbe9a', 1, 21000.00, '', 1, 'BERHASIL', '2025-05-01 15:33:37', '2025-05-01 17:19:15', 0);
INSERT INTO `pr_detail_transaksi` VALUES (54, 8, 81, '681331e1bddd4', 1, 17000.00, '', 1, 'BERHASIL', '2025-05-01 15:33:37', '2025-05-01 17:19:15', 0);
INSERT INTO `pr_detail_transaksi` VALUES (55, 8, 183, '681331e1bdf8c', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 15:33:37', '2025-05-01 17:19:15', 0);
INSERT INTO `pr_detail_transaksi` VALUES (56, 8, 187, '681331e1be11f', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 15:33:37', '2025-05-01 17:19:15', 0);
INSERT INTO `pr_detail_transaksi` VALUES (57, 12, 186, '681348e49852f', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 17:11:48', '2025-05-01 17:58:43', 0);
INSERT INTO `pr_detail_transaksi` VALUES (58, 13, 186, '6813494f67fd1', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 17:13:35', '2025-05-01 18:08:55', 0);
INSERT INTO `pr_detail_transaksi` VALUES (59, 13, 85, '6813494f6806d', 1, 17000.00, '', 1, 'BERHASIL', '2025-05-01 17:13:35', '2025-05-01 18:08:55', 0);
INSERT INTO `pr_detail_transaksi` VALUES (60, 13, 30, '6813494f680e6', 1, 23000.00, '', 1, 'BERHASIL', '2025-05-01 17:13:35', '2025-05-01 18:08:55', 0);
INSERT INTO `pr_detail_transaksi` VALUES (61, 13, 30, '6813494f680e6', 1, 23000.00, '', 1, 'BERHASIL', '2025-05-01 17:13:35', '2025-05-01 18:08:55', 0);
INSERT INTO `pr_detail_transaksi` VALUES (62, 13, 187, '6813494f681c4', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 17:13:35', '2025-05-01 18:08:55', 0);
INSERT INTO `pr_detail_transaksi` VALUES (63, 13, 209, '6813494f703aa', 1, 15000.00, '', 1, 'BERHASIL', '2025-05-01 17:13:35', '2025-05-01 18:08:55', 0);
INSERT INTO `pr_detail_transaksi` VALUES (64, 13, 209, '6813494f703aa', 1, 15000.00, '', 1, 'BERHASIL', '2025-05-01 17:13:35', '2025-05-01 18:08:55', 0);
INSERT INTO `pr_detail_transaksi` VALUES (65, 13, 209, '6813494f703aa', 1, 15000.00, '', 1, 'BERHASIL', '2025-05-01 17:13:35', '2025-05-01 18:08:55', 0);
INSERT INTO `pr_detail_transaksi` VALUES (66, 13, 124, '681350d93f8d5', 1, 4000.00, '', 1, 'BERHASIL', '2025-05-01 17:45:45', '2025-05-01 18:08:55', 0);
INSERT INTO `pr_detail_transaksi` VALUES (67, 13, 124, '681350d93f8d5', 1, 4000.00, '', 1, 'BERHASIL', '2025-05-01 17:45:45', '2025-05-01 18:08:55', 0);
INSERT INTO `pr_detail_transaksi` VALUES (68, 13, 124, '681350d93f8d5', 1, 4000.00, '', 1, 'BERHASIL', '2025-05-01 17:45:45', '2025-05-01 18:08:55', 0);
INSERT INTO `pr_detail_transaksi` VALUES (69, 14, 179, '68135382a76e2', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 17:57:06', '2025-05-01 18:12:00', 0);
INSERT INTO `pr_detail_transaksi` VALUES (70, 14, 124, '68135382afcb4', 1, 4000.00, '', 1, 'BERHASIL', '2025-05-01 17:57:06', '2025-05-01 18:12:00', 0);
INSERT INTO `pr_detail_transaksi` VALUES (71, 14, 129, '68135382aff14', 1, 25000.00, '', 1, 'BERHASIL', '2025-05-01 17:57:06', '2025-05-01 18:12:00', 0);
INSERT INTO `pr_detail_transaksi` VALUES (72, 14, 59, '68135382b0050', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 17:57:06', '2025-05-01 18:12:00', 0);
INSERT INTO `pr_detail_transaksi` VALUES (73, 14, 199, '68135382b01af', 1, 17000.00, '', 1, 'BERHASIL', '2025-05-01 17:57:06', '2025-05-01 18:12:00', 0);
INSERT INTO `pr_detail_transaksi` VALUES (74, 15, 36, '68135e7b7ddbb', 1, 19000.00, '', 1, 'BERHASIL', '2025-05-01 18:43:55', '2025-05-01 18:48:52', 0);
INSERT INTO `pr_detail_transaksi` VALUES (75, 15, 87, '68135e7b7df06', 1, 17000.00, '', 1, 'BATAL', '2025-05-01 18:43:55', '2025-05-01 18:47:23', 0);
INSERT INTO `pr_detail_transaksi` VALUES (76, 15, 109, '68135e7b7f197', 1, 20000.00, '', 1, 'BERHASIL', '2025-05-01 18:43:55', '2025-05-01 18:48:52', 0);
INSERT INTO `pr_detail_transaksi` VALUES (77, 15, 17, '68135e7b7f24a', 1, 12000.00, '', 1, 'BERHASIL', '2025-05-01 18:43:55', '2025-05-01 18:48:52', 0);
INSERT INTO `pr_detail_transaksi` VALUES (78, 15, 56, '68135e7b7f30a', 1, 18000.00, '', 1, 'BATAL', '2025-05-01 18:43:55', '2025-05-01 18:45:48', 0);
INSERT INTO `pr_detail_transaksi` VALUES (79, 15, 140, '68135e7b7f3d5', 1, 30000.00, '', 1, 'BERHASIL', '2025-05-01 18:43:55', '2025-05-01 18:48:52', 0);
INSERT INTO `pr_detail_transaksi` VALUES (80, 15, 201, '68135e7b7f4aa', 1, 20000.00, '', 1, 'BERHASIL', '2025-05-01 18:43:55', '2025-05-01 18:48:52', 0);
INSERT INTO `pr_detail_transaksi` VALUES (81, 15, 182, '68135e7b7f558', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 18:43:55', '2025-05-01 18:48:52', 0);
INSERT INTO `pr_detail_transaksi` VALUES (82, 15, 169, '68135e7b7f5d5', 1, 25000.00, '', 1, 'BERHASIL', '2025-05-01 18:43:55', '2025-05-01 18:48:52', 0);
INSERT INTO `pr_detail_transaksi` VALUES (83, 15, 77, '68135f7c38c4c', 1, 21000.00, '', 1, 'BERHASIL', '2025-05-01 18:48:12', '2025-05-01 18:48:52', 0);
INSERT INTO `pr_detail_transaksi` VALUES (84, 15, 86, '68135f7c38cce', 1, 17000.00, '', 1, 'BERHASIL', '2025-05-01 18:48:12', '2025-05-01 18:48:52', 0);
INSERT INTO `pr_detail_transaksi` VALUES (85, 16, 124, '6813617e5adb3', 1, 4000.00, '', 1, 'BERHASIL', '2025-05-01 18:56:46', '2025-05-01 19:02:26', 0);
INSERT INTO `pr_detail_transaksi` VALUES (86, 16, 186, '6813617e5ae39', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 18:56:46', '2025-05-01 19:02:26', 0);
INSERT INTO `pr_detail_transaksi` VALUES (87, 16, 173, '6813617e5cb10', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 18:56:46', '2025-05-01 19:02:26', 0);
INSERT INTO `pr_detail_transaksi` VALUES (88, 16, 173, '6813617e5cb10', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 18:56:46', '2025-05-01 19:02:26', 0);
INSERT INTO `pr_detail_transaksi` VALUES (89, 16, 83, '6813617e5cc72', 1, 15000.00, '', 1, 'BATAL', '2025-05-01 18:56:46', '2025-05-01 18:57:50', 0);
INSERT INTO `pr_detail_transaksi` VALUES (90, 16, 69, '6813617e5cce0', 1, 20000.00, '', 1, 'BERHASIL', '2025-05-01 18:56:46', '2025-05-01 19:02:26', 0);
INSERT INTO `pr_detail_transaksi` VALUES (91, 16, 77, '6813617e5cd4a', 1, 21000.00, '', 1, 'BERHASIL', '2025-05-01 18:56:46', '2025-05-01 19:02:26', 0);
INSERT INTO `pr_detail_transaksi` VALUES (92, 16, 204, '6813617e5cdaa', 1, 15000.00, '', 1, 'BERHASIL', '2025-05-01 18:56:46', '2025-05-01 19:02:26', 0);
INSERT INTO `pr_detail_transaksi` VALUES (93, 16, 197, '6813617e5ce04', 1, 20000.00, '', 1, 'BERHASIL', '2025-05-01 18:56:46', '2025-05-01 19:02:26', 0);
INSERT INTO `pr_detail_transaksi` VALUES (94, 16, 165, '6813617e5ce4e', 1, 28000.00, '', 1, 'BERHASIL', '2025-05-01 18:56:46', '2025-05-01 19:02:26', 0);
INSERT INTO `pr_detail_transaksi` VALUES (95, 16, 157, '6813617e5ce9b', 1, 23000.00, '', 1, 'BERHASIL', '2025-05-01 18:56:46', '2025-05-01 19:02:26', 0);
INSERT INTO `pr_detail_transaksi` VALUES (96, 16, 84, '681361d107c08', 1, 16000.00, '', 1, 'BERHASIL', '2025-05-01 18:58:09', '2025-05-01 19:02:26', 0);
INSERT INTO `pr_detail_transaksi` VALUES (97, 17, 146, '681364da1b835', 1, 22000.00, '', 1, 'BERHASIL', '2025-05-01 19:11:06', '2025-05-01 19:11:39', 0);
INSERT INTO `pr_detail_transaksi` VALUES (98, 17, 202, '681364da1b8cb', 1, 22000.00, '', 1, 'BERHASIL', '2025-05-01 19:11:06', '2025-05-01 19:11:39', 0);
INSERT INTO `pr_detail_transaksi` VALUES (99, 18, 46, '681366866f92a', 1, 21000.00, '', 1, 'BERHASIL', '2025-05-01 19:18:14', '2025-05-01 19:18:44', 0);
INSERT INTO `pr_detail_transaksi` VALUES (100, 18, 37, '6813668670cc7', 1, 22000.00, '', 1, 'BERHASIL', '2025-05-01 19:18:14', '2025-05-01 19:18:44', 0);
INSERT INTO `pr_detail_transaksi` VALUES (101, 18, 182, '6813668670da8', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 19:18:14', '2025-05-01 19:18:44', 0);
INSERT INTO `pr_detail_transaksi` VALUES (102, 18, 183, '6813668670e21', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 19:18:14', '2025-05-01 19:18:44', 0);
INSERT INTO `pr_detail_transaksi` VALUES (103, 19, 169, '68136c77525e6', 1, 25000.00, '', 1, 'BERHASIL', '2025-05-01 19:43:35', '2025-05-01 19:44:09', 0);
INSERT INTO `pr_detail_transaksi` VALUES (104, 19, 64, '68136c775269a', 1, 20000.00, '', 1, 'BERHASIL', '2025-05-01 19:43:35', '2025-05-01 19:44:09', 0);
INSERT INTO `pr_detail_transaksi` VALUES (105, 19, 6, '68136c775a558', 1, 21000.00, '', 1, 'BERHASIL', '2025-05-01 19:43:35', '2025-05-01 19:44:09', 0);
INSERT INTO `pr_detail_transaksi` VALUES (106, 19, 179, '68136c775a6b8', 1, 18000.00, '', 1, 'BERHASIL', '2025-05-01 19:43:35', '2025-05-01 19:44:09', 0);
INSERT INTO `pr_detail_transaksi` VALUES (107, 20, 185, '6813774f7dc95', 1, 15000.00, '', 1, 'BERHASIL', '2025-05-01 20:29:51', '2025-05-01 21:11:01', 0);
INSERT INTO `pr_detail_transaksi` VALUES (108, 20, 124, '6813774f7e878', 1, 4000.00, '', 1, 'BERHASIL', '2025-05-01 20:29:51', '2025-05-01 21:11:01', 0);
INSERT INTO `pr_detail_transaksi` VALUES (109, 20, 126, '6813774f7e914', 1, 25000.00, '', 1, 'BERHASIL', '2025-05-01 20:29:51', '2025-05-01 21:11:01', 0);

SET FOREIGN_KEY_CHECKS = 1;
