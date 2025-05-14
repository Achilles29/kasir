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

 Date: 12/05/2025 06:03:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_voucher
-- ----------------------------
DROP TABLE IF EXISTS `pr_voucher`;
CREATE TABLE `pr_voucher`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_voucher` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `jenis` enum('persentase','nominal','gratis_produk','cashback','min_pembelian') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nilai` int(11) NOT NULL,
  `min_pembelian` int(11) NULL DEFAULT 0,
  `produk_id` int(11) NULL DEFAULT NULL,
  `jumlah_gratis` int(11) NULL DEFAULT 0,
  `max_diskon` int(11) NULL DEFAULT NULL,
  `maksimal_voucher` int(11) NULL DEFAULT NULL,
  `sisa_voucher` int(11) NULL DEFAULT NULL,
  `status` enum('aktif','nonaktif') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'aktif',
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date NOT NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `pr_transaksi_id` int(11) NULL DEFAULT NULL,
  `customer_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode_voucher`(`kode_voucher`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
