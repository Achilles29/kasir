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

 Date: 01/05/2025 15:37:08
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pr_detail_extra
-- ----------------------------
DROP TABLE IF EXISTS `pr_detail_extra`;
CREATE TABLE `pr_detail_extra`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detail_transaksi_id` int(11) NOT NULL,
  `pr_produk_extra_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `harga` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `sku` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `satuan` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `hpp` int(11) NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `status` enum('BERHASIL','BATAL','REFUND') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `detail_transaksi_id`(`detail_transaksi_id`) USING BTREE,
  INDEX `pr_produk_extra_id`(`pr_produk_extra_id`) USING BTREE,
  CONSTRAINT `pr_detail_extra_ibfk_1` FOREIGN KEY (`detail_transaksi_id`) REFERENCES `pr_detail_transaksi` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `pr_detail_extra_ibfk_2` FOREIGN KEY (`pr_produk_extra_id`) REFERENCES `pr_produk_extra` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 120 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
