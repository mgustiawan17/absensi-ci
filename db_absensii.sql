/*
 Navicat Premium Data Transfer

 Source Server         : MYSQL LOCAL
 Source Server Type    : MySQL
 Source Server Version : 50733
 Source Host           : localhost:3306
 Source Schema         : db_absensi

 Target Server Type    : MySQL
 Target Server Version : 50733
 File Encoding         : 65001

 Date: 13/09/2023 13:50:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_absen_susulan
-- ----------------------------
DROP TABLE IF EXISTS `t_absen_susulan`;
CREATE TABLE `t_absen_susulan`  (
  `id_absen_susulan` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) NOT NULL,
  `id_atasan` int(11) NOT NULL,
  `tanggal_absen` date NOT NULL,
  `id_alasan` int(11) NOT NULL,
  `keterangan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `approval_atasan` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `created_date` date NOT NULL,
  `created_user` int(11) NOT NULL,
  `updated_date` date NOT NULL,
  `updated_user` int(11) NOT NULL,
  `active` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_absen_susulan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_absen_susulan
-- ----------------------------
INSERT INTO `t_absen_susulan` VALUES (1, 4, 1, '2014-11-03', 1, 'Sakit', '1', '2014-11-25', 4, '2014-11-26', 1, '1');
INSERT INTO `t_absen_susulan` VALUES (2, 4, 1, '2014-11-04', 1, 'Sakit', '1', '2014-11-25', 4, '2014-11-26', 1, '1');
INSERT INTO `t_absen_susulan` VALUES (3, 3, 1, '2014-10-11', 2, 'cuti', '1', '2014-11-25', 3, '2014-11-26', 1, '1');
INSERT INTO `t_absen_susulan` VALUES (4, 3, 1, '2014-11-11', 2, 'Cuti', '1', '2014-11-25', 3, '2014-11-26', 1, '1');
INSERT INTO `t_absen_susulan` VALUES (5, 2, 5, '2014-07-11', 6, 'Lupa absen', '0', '2014-11-26', 2, '2023-08-18', 0, '1');

-- ----------------------------
-- Table structure for t_alasan
-- ----------------------------
DROP TABLE IF EXISTS `t_alasan`;
CREATE TABLE `t_alasan`  (
  `id_alasan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_alasan` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_date` date NOT NULL,
  `created_user` int(11) NOT NULL,
  `updated_date` date NOT NULL,
  `updated_user` int(11) NOT NULL,
  `active` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_alasan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_alasan
-- ----------------------------
INSERT INTO `t_alasan` VALUES (1, 'Sakit', '2014-11-04', 1, '2014-11-04', 1, '1');
INSERT INTO `t_alasan` VALUES (2, 'Cuti', '2014-11-04', 1, '2023-08-21', 0, '1');
INSERT INTO `t_alasan` VALUES (3, 'Ijin', '2014-11-04', 1, '2023-08-21', 0, '1');
INSERT INTO `t_alasan` VALUES (4, 'Lainnya', '2014-11-04', 1, '2023-08-21', 0, '1');
INSERT INTO `t_alasan` VALUES (5, 'Masuk', '2014-11-24', 1, '2023-08-21', 1, '1');
INSERT INTO `t_alasan` VALUES (6, 'Lupa Absen', '2014-11-26', 1, '2023-08-21', 0, '1');

-- ----------------------------
-- Table structure for t_divisi
-- ----------------------------
DROP TABLE IF EXISTS `t_divisi`;
CREATE TABLE `t_divisi`  (
  `id_divisi` int(11) NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_date` date NOT NULL,
  `created_user` int(11) NOT NULL,
  `updated_date` date NOT NULL,
  `updated_user` int(11) NOT NULL,
  `active` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_divisi`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_divisi
-- ----------------------------
INSERT INTO `t_divisi` VALUES (1, 'Pengajar', '2014-11-04', 1, '2023-08-17', 6, '1');
INSERT INTO `t_divisi` VALUES (2, 'Tata Usaha', '2014-11-04', 1, '2023-08-17', 6, '1');
INSERT INTO `t_divisi` VALUES (3, 'Divisi Finance', '2014-11-04', 1, '2014-11-04', 1, '1');

-- ----------------------------
-- Table structure for t_golongan
-- ----------------------------
DROP TABLE IF EXISTS `t_golongan`;
CREATE TABLE `t_golongan`  (
  `id_golongan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_golongan` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_date` date NOT NULL,
  `created_user` int(11) NOT NULL,
  `updated_date` date NOT NULL,
  `updated_user` int(11) NOT NULL,
  `active` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_golongan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_golongan
-- ----------------------------
INSERT INTO `t_golongan` VALUES (1, 'Golongan 1', '2014-11-04', 1, '2014-11-04', 1, '1');
INSERT INTO `t_golongan` VALUES (2, 'Golongan 2', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_golongan` VALUES (3, 'Golongan 3', '2014-11-04', 1, '2014-11-04', 1, '1');
INSERT INTO `t_golongan` VALUES (4, 'Golongan 4', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_golongan` VALUES (5, 'Golongan 5', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_golongan` VALUES (6, 'Golongan 6', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_golongan` VALUES (7, 'Golongan 7', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_golongan` VALUES (8, 'Golongan 8', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_golongan` VALUES (9, 'Golongan 9', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_golongan` VALUES (10, 'Golongan 10', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_golongan` VALUES (11, 'Golongan 11', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_golongan` VALUES (12, 'Golongan 12', '2014-11-04', 1, '0000-00-00', 0, '1');

-- ----------------------------
-- Table structure for t_jabatan
-- ----------------------------
DROP TABLE IF EXISTS `t_jabatan`;
CREATE TABLE `t_jabatan`  (
  `id_jabatan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_date` date NOT NULL,
  `created_user` int(11) NOT NULL,
  `updated_date` date NOT NULL,
  `updated_user` int(11) NOT NULL,
  `active` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_jabatan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_jabatan
-- ----------------------------
INSERT INTO `t_jabatan` VALUES (1, 'administrator', '2014-11-04', 1, '2014-11-04', 1, '1');
INSERT INTO `t_jabatan` VALUES (2, 'Direktur', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_jabatan` VALUES (3, 'Manager', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_jabatan` VALUES (4, 'Supervisor', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_jabatan` VALUES (5, 'Staff', '2014-11-04', 1, '2014-11-04', 1, '1');

-- ----------------------------
-- Table structure for t_jam_kerja
-- ----------------------------
DROP TABLE IF EXISTS `t_jam_kerja`;
CREATE TABLE `t_jam_kerja`  (
  `id_jam_kerja` int(11) NOT NULL AUTO_INCREMENT,
  `jam_masuk` time(0) NULL DEFAULT NULL,
  `jam_keluar` time(0) NULL DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `active` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '1',
  `created_user` int(11) NULL DEFAULT NULL,
  `created_date` date NULL DEFAULT NULL,
  `updated_user` int(11) NULL DEFAULT NULL,
  `updated_date` date NULL DEFAULT NULL,
  PRIMARY KEY (`id_jam_kerja`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_jam_kerja
-- ----------------------------
INSERT INTO `t_jam_kerja` VALUES (1, '08:00:00', '17:00:00', '08.00 - 17.00', '1', 1, '2014-11-13', 1, '2014-11-13');
INSERT INTO `t_jam_kerja` VALUES (2, '08:30:00', '17:30:00', '08.30 - 17.30', '1', 1, '2014-11-13', 0, '0000-00-00');
INSERT INTO `t_jam_kerja` VALUES (3, '09:00:00', '18:00:00', '09:00 - 18:00', '1', 1, '2014-11-13', 1, '2014-11-13');
INSERT INTO `t_jam_kerja` VALUES (4, '07:30:00', '16:30:00', '07:30 - 16:30', '1', 1, '2014-11-18', 1, '2014-11-18');
INSERT INTO `t_jam_kerja` VALUES (5, '20:00:00', '09:37:00', 'TEST', '1', 1, '2023-08-14', 1, '2023-08-14');

-- ----------------------------
-- Table structure for t_karyawan
-- ----------------------------
DROP TABLE IF EXISTS `t_karyawan`;
CREATE TABLE `t_karyawan`  (
  `id_karyawan` int(11) NOT NULL AUTO_INCREMENT,
  `nik` char(12) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tanggal_lahir` date NULL DEFAULT NULL,
  `jenis_kelamin` enum('1','2') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '1',
  `alamat` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `no_telp` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-',
  `no_handphone` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_perkawinan` enum('0','1','2','3') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0',
  `id_jabatan` int(11) NULL DEFAULT NULL,
  `id_golongan` int(11) NULL DEFAULT NULL,
  `id_divisi` int(11) NULL DEFAULT NULL,
  `id_jam_kerja` int(11) NULL DEFAULT NULL,
  `tanggal_masuk` date NULL DEFAULT NULL,
  `password` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_date` datetime(0) NULL DEFAULT NULL,
  `created_user` int(11) NULL DEFAULT NULL,
  `updated_date` datetime(0) NULL DEFAULT NULL,
  `updated_user` int(11) NULL DEFAULT NULL,
  `active` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0',
  `rfid` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_karyawan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_karyawan
-- ----------------------------
INSERT INTO `t_karyawan` VALUES (1, '000001', 'Administrator', 'Bandung', '2014-11-04', '1', 'Jalan Rereongan Sarupi No 41', '0', '081220493870', 'soniibrol@gmail.com', '1', 1, 1, 1, 5, '2014-11-04', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2014-11-04 00:00:00', 1, '2023-08-14 13:39:30', 1, '1', NULL, NULL);
INSERT INTO `t_karyawan` VALUES (2, '000002', 'Soni Rahayu', 'Bandung', '1990-09-06', '1', 'Jalan Rereongan Sarupi No 41 Ciumbuleuit Bandung 40142', '0', '081220493870', 'soniibrol@gmail.com', '1', 5, 7, 2, 3, '2014-09-01', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2014-11-05 08:49:13', 1, '2014-11-24 05:03:29', 1, '1', NULL, NULL);
INSERT INTO `t_karyawan` VALUES (3, '000003', 'Riska Rosiana', 'Bandung', '1989-10-18', '2', 'Jalan Raya Cibeureum Bandung', '0', '085635355321', 'riskarosiana@gmail.com', '1', 5, 7, 3, 1, '2012-01-01', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2014-11-05 08:51:05', 1, '2014-11-24 05:03:35', 1, '1', NULL, NULL);
INSERT INTO `t_karyawan` VALUES (4, '000004', 'Randdy Rinaldi Priatna', 'Bandung', '1990-01-06', '1', 'Jalan Turangga Bandung', '0', '0857181718171', 'randdypriatna@gmail.com', '1', 5, 7, 1, 1, '2014-01-05', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2014-11-05 08:53:14', 1, '2014-11-24 05:03:40', 1, '1', NULL, NULL);
INSERT INTO `t_karyawan` VALUES (5, '055', 'Rian Fathurahman', 'Bandung', '1981-03-01', '1', 'Jalan Pengangsaan No 55', '0', '088716616521', 'rian@absensi.com', '2', 4, 8, 2, 3, '2010-02-09', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2014-11-26 09:14:16', 1, '2023-08-17 13:20:11', 6, '1', '1232', NULL);
INSERT INTO `t_karyawan` VALUES (6, '123', 'Saepul', 'Bandung', '1995-12-01', '1', 'Jl', '0', '0887123123123', 'saepul@gmail.com', '2', 1, 3, 1, 1, '2023-08-14', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2023-08-14 15:21:42', 1, NULL, NULL, '1', '0004713311', NULL);
INSERT INTO `t_karyawan` VALUES (24, '1313123', 'herman', 'Bandung', '2023-08-20', '1', 'sdasdas', '0', '12312', 'admin@admin.com', '1', 1, 8, 1, 1, '2023-08-20', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2023-08-20 11:54:50', 6, NULL, NULL, '1', '123132123', '1313123');
INSERT INTO `t_karyawan` VALUES (25, '4141342323', 'herman', 'Bandung', '2023-08-20', '1', 'asdadssad', '0', '75464', 'admin@admin.com', '1', 1, 10, 1, 1, '2023-08-20', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2023-08-20 12:15:12', 6, NULL, NULL, '1', '45353453454', '4141342323');
INSERT INTO `t_karyawan` VALUES (26, '1312312312', '1231', 'Bandung', '2023-08-20', '1', 'adsasd', '0', '656742', 'admin@admin.com', '2', 1, 11, 1, 1, '2023-08-20', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2023-08-20 12:20:08', 6, NULL, NULL, '1', '123123', '1312312312.png');
INSERT INTO `t_karyawan` VALUES (27, '1312312312', 'herman', 'Bandung', '2023-08-20', '1', 'jlwere', '0', '132323', 'admin@admin.com', '1', 1, 10, 1, 1, '2023-08-20', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2023-08-20 12:25:51', 6, NULL, NULL, '1', '123123', '1312312312.png');
INSERT INTO `t_karyawan` VALUES (28, '1312312312', 'herman', '12323', '2023-08-20', '1', 'adasdsa', '0', '1232', 'admin@admin.com', '1', 1, 7, 1, 1, '2023-08-20', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2023-08-20 12:29:01', 6, NULL, NULL, '1', '123123132', '1312312312.png');

-- ----------------------------
-- Table structure for t_kehadiran
-- ----------------------------
DROP TABLE IF EXISTS `t_kehadiran`;
CREATE TABLE `t_kehadiran`  (
  `id_kehadiran` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` datetime(0) NOT NULL,
  `jam_keluar` datetime(0) NULL DEFAULT NULL,
  `hadir` enum('1','2') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '2',
  `id_alasan` int(11) NOT NULL,
  `id_absen_susulan` int(11) NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_date` date NULL DEFAULT NULL,
  `created_user` int(11) NULL DEFAULT NULL,
  `updated_date` timestamp(0) NULL DEFAULT NULL,
  `updated_user` int(11) NULL DEFAULT NULL,
  `active` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0',
  PRIMARY KEY (`id_kehadiran`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_kehadiran
-- ----------------------------
INSERT INTO `t_kehadiran` VALUES (1, 1, '2014-10-01', '2014-10-01 08:40:00', '2014-11-24 18:09:00', '1', 5, 0, '', '2014-10-24', 1, '2014-11-24 07:00:00', 1, '1');
INSERT INTO `t_kehadiran` VALUES (3, 3, '2014-11-24', '2014-11-24 07:18:45', '2014-11-24 17:20:02', '1', 5, 0, '', '2014-11-24', 3, '2014-11-24 07:00:00', 3, '1');
INSERT INTO `t_kehadiran` VALUES (4, 2, '2014-11-25', '2014-11-25 04:27:12', '0000-00-00 00:00:00', '1', 5, 0, '', '2014-11-25', 2, '0000-00-00 00:00:00', 0, '1');
INSERT INTO `t_kehadiran` VALUES (8, 3, '2014-11-25', '2014-11-25 08:31:37', '2014-11-25 17:36:11', '1', 5, 0, '', '2014-11-25', 3, '2014-11-25 07:00:00', 3, '1');
INSERT INTO `t_kehadiran` VALUES (10, 4, '2014-11-25', '2014-11-25 04:38:16', '0000-00-00 00:00:00', '1', 5, 0, '', '2014-11-25', 4, '0000-00-00 00:00:00', 0, '1');
INSERT INTO `t_kehadiran` VALUES (11, 4, '2014-11-26', '2014-11-26 04:53:23', '0000-00-00 00:00:00', '1', 5, 0, '', '2014-11-26', 4, '0000-00-00 00:00:00', 0, '1');
INSERT INTO `t_kehadiran` VALUES (12, 3, '2014-11-26', '2014-11-26 04:54:10', '0000-00-00 00:00:00', '1', 5, 0, '', '2014-11-26', 3, '0000-00-00 00:00:00', 0, '1');
INSERT INTO `t_kehadiran` VALUES (13, 5, '2014-11-26', '2014-11-26 09:14:56', '0000-00-00 00:00:00', '1', 5, 0, '', '2014-11-26', 5, '0000-00-00 00:00:00', 0, '1');
INSERT INTO `t_kehadiran` VALUES (14, 3, '2014-10-11', '2014-10-11 08:00:00', '2014-10-11 18:00:00', '1', 2, 3, '(Absen Susulan Approved on 26-11-2014 09:41:17)', '2014-11-26', 1, '2014-11-26 07:00:00', 1, '1');
INSERT INTO `t_kehadiran` VALUES (15, 3, '2014-11-11', '2014-11-11 08:00:00', '2014-11-11 18:00:00', '1', 2, 4, '(Absen Susulan Approved on 26-11-2014 09:41:25)', '2014-11-26', 1, '2014-11-26 07:00:00', 1, '1');
INSERT INTO `t_kehadiran` VALUES (16, 4, '2014-11-03', '2014-11-03 08:00:00', '2014-11-03 18:00:00', '1', 1, 1, '(Absen Susulan Approved on 26-11-2014 09:42:35)', '2014-11-26', 1, '2014-11-26 07:00:00', 1, '1');
INSERT INTO `t_kehadiran` VALUES (17, 4, '2014-11-04', '2014-11-04 08:00:00', '2014-11-04 18:00:00', '1', 1, 2, '(Absen Susulan Approved on 26-11-2014 09:42:38)', '2014-11-26', 1, '2014-11-26 07:00:00', 1, '1');
INSERT INTO `t_kehadiran` VALUES (30, 6, '2023-08-14', '2023-08-14 23:22:17', '2023-08-14 23:35:21', '1', 5, NULL, NULL, '2023-08-14', 1, '2023-08-14 00:00:00', 1, '1');
INSERT INTO `t_kehadiran` VALUES (31, 6, '2023-08-20', '2023-08-20 20:45:38', '2023-08-20 20:46:16', '1', 5, NULL, NULL, '2023-08-20', 6, '2023-08-20 00:00:00', 6, '1');
INSERT INTO `t_kehadiran` VALUES (32, 5, '2023-08-20', '2023-08-20 20:47:05', '2023-08-20 20:47:08', '1', 5, NULL, NULL, '2023-08-20', 6, '2023-08-20 00:00:00', 6, '1');

-- ----------------------------
-- Table structure for t_perencanaan_harikerja
-- ----------------------------
DROP TABLE IF EXISTS `t_perencanaan_harikerja`;
CREATE TABLE `t_perencanaan_harikerja`  (
  `id_perencanaan` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bulan` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tahun` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `keterangan` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `created_date` date NOT NULL,
  `created_user` int(11) NOT NULL,
  `updated_user` int(11) NOT NULL,
  `updated_date` date NOT NULL,
  `active` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_perencanaan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 121 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_perencanaan_harikerja
-- ----------------------------
INSERT INTO `t_perencanaan_harikerja` VALUES (1, '1', '11', '2014', '0', 'weekend', '2014-11-11', 1, 1, '2014-11-12', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (2, '2', '11', '2014', '0', 'weekend', '2014-11-11', 1, 1, '2014-11-12', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (3, '3', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (4, '4', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (5, '5', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (6, '6', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (7, '7', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (8, '8', '11', '2014', '0', 'weekend', '2014-11-11', 1, 1, '2014-11-12', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (9, '9', '11', '2014', '0', 'weekend', '2014-11-11', 1, 1, '2014-11-12', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (10, '10', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (11, '11', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (12, '12', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (13, '13', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (14, '14', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (15, '15', '11', '2014', '0', 'weekend', '2014-11-11', 1, 1, '2014-11-12', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (16, '16', '11', '2014', '0', 'weekend', '2014-11-11', 1, 1, '2014-11-12', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (17, '17', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (18, '18', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (19, '19', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (20, '20', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (21, '21', '11', '2014', '1', '', '2014-11-11', 1, 1, '2014-11-12', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (22, '22', '11', '2014', '0', 'weekend', '2014-11-11', 1, 1, '2014-11-12', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (23, '23', '11', '2014', '0', 'weekend', '2014-11-11', 1, 1, '2014-11-12', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (24, '24', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (25, '25', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (26, '26', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (27, '27', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (28, '28', '11', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (29, '29', '11', '2014', '0', 'weekend', '2014-11-11', 1, 1, '2014-11-12', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (30, '30', '11', '2014', '0', 'weekend', '2014-11-11', 1, 1, '2014-11-12', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (31, '1', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (32, '2', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (33, '3', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (34, '4', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (35, '5', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (36, '6', '12', '2014', '0', 'weekend', '2014-11-11', 1, 1, '2014-11-18', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (37, '7', '12', '2014', '0', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (38, '8', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (39, '9', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (40, '10', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (41, '11', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (42, '12', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (43, '13', '12', '2014', '0', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (44, '14', '12', '2014', '0', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (45, '15', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (46, '16', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (47, '17', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (48, '18', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (49, '19', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (50, '20', '12', '2014', '0', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (51, '21', '12', '2014', '0', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (52, '22', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (53, '23', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (54, '24', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (55, '25', '12', '2014', '0', 'Hari Natal', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (56, '26', '12', '2014', '0', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (57, '27', '12', '2014', '0', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (58, '28', '12', '2014', '0', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (59, '29', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (60, '30', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (61, '31', '12', '2014', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (62, '1', '1', '2015', '0', 'Tahun Baru 2015', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (63, '2', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (64, '3', '1', '2015', '0', 'weekend', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (65, '4', '1', '2015', '0', 'weekend', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (66, '5', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (67, '6', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (68, '7', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (69, '8', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (70, '9', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (71, '10', '1', '2015', '0', 'weekend', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (72, '11', '1', '2015', '0', 'weekend', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (73, '12', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (74, '13', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (75, '14', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (76, '15', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (77, '16', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (78, '17', '1', '2015', '0', 'weekend', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (79, '18', '1', '2015', '0', 'weekend', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (80, '19', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (81, '20', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (82, '21', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (83, '22', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (84, '23', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (85, '24', '1', '2015', '0', 'weekend', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (86, '25', '1', '2015', '0', 'weekend', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (87, '26', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (88, '27', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (89, '28', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (90, '29', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (91, '30', '1', '2015', '1', '', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (92, '31', '1', '2015', '0', 'weekend', '2014-11-11', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (93, '1', '2', '2015', '0', 'weekend', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (94, '2', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (95, '3', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (96, '4', '2', '2015', '1', 'tetete', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (97, '5', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (98, '6', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (99, '7', '2', '2015', '0', 'weekend', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (100, '8', '2', '2015', '0', 'weekend', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (101, '9', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (102, '10', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (103, '11', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (104, '12', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (105, '13', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (106, '14', '2', '2015', '0', 'weekend', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (107, '15', '2', '2015', '0', 'weekend', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (108, '16', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (109, '17', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (110, '18', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (111, '19', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (112, '20', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (113, '21', '2', '2015', '0', 'weekend', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (114, '22', '2', '2015', '0', 'weekend', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (115, '23', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (116, '24', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (117, '25', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (118, '26', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (119, '27', '2', '2015', '1', '', '2014-11-12', 1, 0, '0000-00-00', '1');
INSERT INTO `t_perencanaan_harikerja` VALUES (120, '28', '2', '2015', '0', 'weekend', '2014-11-12', 1, 0, '0000-00-00', '1');

-- ----------------------------
-- Table structure for t_user_type
-- ----------------------------
DROP TABLE IF EXISTS `t_user_type`;
CREATE TABLE `t_user_type`  (
  `id_user_type` int(11) NOT NULL AUTO_INCREMENT,
  `id_jabatan` int(11) NOT NULL,
  `nama_user_type` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_date` date NOT NULL,
  `created_user` int(11) NOT NULL,
  `updated_date` date NOT NULL,
  `updated_user` int(11) NOT NULL,
  `active` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_user_type
-- ----------------------------
INSERT INTO `t_user_type` VALUES (1, 1, 'administrator', '2014-11-04', 1, '2014-11-04', 1, '1');
INSERT INTO `t_user_type` VALUES (2, 2, 'direktur', '2014-11-04', 1, '2014-11-04', 1, '1');
INSERT INTO `t_user_type` VALUES (3, 3, 'manager', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_user_type` VALUES (4, 4, 'supervisor', '2014-11-04', 1, '0000-00-00', 0, '1');
INSERT INTO `t_user_type` VALUES (5, 5, 'staff', '2014-11-04', 1, '0000-00-00', 0, '1');

SET FOREIGN_KEY_CHECKS = 1;
