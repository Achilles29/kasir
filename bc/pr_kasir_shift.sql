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

 Date: 13/05/2025 20:19:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_kasir_shift
-- ----------------------------
DROP TABLE IF EXISTS `pr_kasir_shift`;
CREATE TABLE `pr_kasir_shift`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kasir_id` int(11) NOT NULL,
  `modal_awal` decimal(15, 2) NOT NULL,
  `waktu_mulai` datetime(0) NOT NULL,
  `total_penjualan` decimal(15, 2) NULL DEFAULT 0,
  `total_pending` decimal(15, 2) NULL DEFAULT 0,
  `modal_akhir` decimal(15, 2) NULL DEFAULT 0,
  `selisih` decimal(15, 2) NULL DEFAULT 0,
  `waktu_tutup` datetime(0) NULL DEFAULT NULL,
  `total_pendapatan` decimal(15, 2) NULL DEFAULT 0,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` enum('OPEN','CLOSE') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'OPEN',
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `transaksi_selesai` int(11) NULL DEFAULT 0,
  `transaksi_pending` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
