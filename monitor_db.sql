/*
Navicat MySQL Data Transfer

Source Server         : 10.134.159.93
Source Server Version : 50711
Source Host           : 10.134.159.93:3306
Source Database       : monitor_db

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2017-03-28 17:23:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for think_admin
-- ----------------------------
DROP TABLE IF EXISTS `think_admin`;
CREATE TABLE `think_admin` (
  `admin_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员账号id',
  `account` varchar(64) NOT NULL COMMENT '登陆账号',
  `password` char(32) NOT NULL COMMENT '密码',
  `creat_time` int(10) unsigned DEFAULT NULL COMMENT '账号创建时间',
  PRIMARY KEY (`admin_id`,`account`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_admin
-- ----------------------------
INSERT INTO `think_admin` VALUES ('1', 'admin', 'admin', null);
INSERT INTO `think_admin` VALUES ('2', 'F3233253', '123456', '1488877493');

-- ----------------------------
-- Table structure for think_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_group`;
CREATE TABLE `think_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_auth_group
-- ----------------------------
INSERT INTO `think_auth_group` VALUES ('1', '测试员', '1', '1,2,3,4,5,6,7,8,9,10,11,');

-- ----------------------------
-- Table structure for think_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_group_access`;
CREATE TABLE `think_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_auth_group_access
-- ----------------------------
INSERT INTO `think_auth_group_access` VALUES ('1', '1');
INSERT INTO `think_auth_group_access` VALUES ('14', '1');

-- ----------------------------
-- Table structure for think_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_rule`;
CREATE TABLE `think_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_auth_rule
-- ----------------------------
INSERT INTO `think_auth_rule` VALUES ('1', 'index-index-getdate', '获取信息', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('2', 'index-index-indexapi', 'indexapi', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('3', 'admin-login-creataccount', '创建账号', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('4', 'api-V1.monitor-getServerList', '服务器列表查看', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('5', 'api-V1.monitor-addServer', '服務器添加', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('6', 'api-V1.monitor-getServerApplyList', '服务器申请列表查看', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('7', 'api-V1.monitor-updateServerStatus', '服务器状态编辑', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('8', 'api-V1.monitor-updateServer', '服务器信息修改', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('9', 'api-V1.monitor-serviceApply', '服务器申请', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('10', 'api-V1.monitor-updateServerApply', '服务器申请信息修改', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('11', 'api-V1.monitor-updateServerApplyStatus', '服务器申请状态修改', '1', '1', '');

-- ----------------------------
-- Table structure for think_deviceinfo
-- ----------------------------
DROP TABLE IF EXISTS `think_deviceinfo`;
CREATE TABLE `think_deviceinfo` (
  `id` varchar(50) NOT NULL COMMENT 'UUID編號',
  `ip` varchar(15) NOT NULL COMMENT 'IP地址',
  `type` tinyint(2) unsigned NOT NULL COMMENT '實體機 or 虛擬機',
  `size` text COMMENT '規格',
  `os` text NOT NULL COMMENT 'OS版本',
  `disk` varchar(50) NOT NULL COMMENT '硬盤',
  `ram` varchar(50) NOT NULL COMMENT '內存條',
  `cpuNum` varchar(50) NOT NULL COMMENT 'CPU',
  `remarks` longtext COMMENT '備註',
  `location` varchar(100) DEFAULT NULL COMMENT '所屬位置',
  `sn_Description` varchar(100) NOT NULL COMMENT '實體機編號 or 虛擬機名稱',
  `warranty` int(10) unsigned DEFAULT NULL COMMENT '维保期',
  `ilo` text COMMENT 'ILO(服务器远程管理)',
  `isValid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有效 Y-有效 N-无效',
  `creator` varchar(50) NOT NULL COMMENT '創建人',
  `createDate` int(10) unsigned NOT NULL COMMENT '創建時間',
  `modified` varchar(50) DEFAULT '' COMMENT '修改者',
  `modifyDate` int(10) unsigned DEFAULT NULL COMMENT '修改日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_deviceinfo
-- ----------------------------
INSERT INTO `think_deviceinfo` VALUES ('0481a144-0e00-11e7-9a8b-d02788486e90', '1', '1', '1', 'shanghai', '1', '1', '1', '1;2;3;4', '1', '1', '1490077375', '1', '1', '14', '1490077883', null, null);
INSERT INTO `think_deviceinfo` VALUES ('eb1d4cb1-0e07-11e7-9a8b-d02788486e90', '10.130.2.95', '1', '', 'Win7', '500G', '4G', '4', ';;;', '', '1', null, '', '1', '14', '1490081277', null, null);
INSERT INTO `think_deviceinfo` VALUES ('10dce81d-0e08-11e7-9a8b-d02788486e90', '10.130.2.230', '1', '', 'Win7', '500G', '4G', '4', ';;;', '', '1', '0', '', '0', 'F3233253 ', '1490081340', 'F3233253 ', '1490163906');
INSERT INTO `think_deviceinfo` VALUES ('22a07ac5-0e08-11e7-9a8b-d02788486e90', '10.130.2.95', '1', '', 'Win7', '500G', '4G', '4', ';;;', '', '', '0', '', '0', 'F3233253 ', '1490081370', 'F3233253 ', '1490164220');
INSERT INTO `think_deviceinfo` VALUES ('d5d6052c-0ea4-11e7-9a8b-d02788486e90', '10.130.2.250', '0', '', 'Linux', '20', '10', '3', 'me;keyi;shiyong;le', '', '12306', '1490148705', '', '0', 'F3233253 ', '1490148671', 'F3233253 ', '1490233615');
INSERT INTO `think_deviceinfo` VALUES ('4c95734c-0eae-11e7-9a8b-d02788486e90', '10.130.2.96', '1', '', 'WinXp', '1', '1', '1', ';;;', '', '1', '1490152771', '', '1', 'F3233253 ', '1490152736', 'F3233253 ', '1490234298');
INSERT INTO `think_deviceinfo` VALUES ('b7e41556-0eb1-11e7-9a8b-d02788486e90', 'ceshi', '1', '', 'Win7', '1', '1', '1', ';;;', '', '', '1489734000', '', '0', 'F3233253 ', '1490154205', null, null);
INSERT INTO `think_deviceinfo` VALUES ('157b049d-0ede-11e7-9a8b-d02788486e90', '10.130.2.220', '1', '', 'Mac Os', '500G', '4G', '2', '周興鵬;k開發;;', '', '001', null, '', '0', 'F3233253 ', '1490173259', null, null);
INSERT INTO `think_deviceinfo` VALUES ('355b46da-103e-11e7-9a8b-d02788486e90', '10.151.126.31', '1', '', 'Winservice2008', '500G', '8G', '8', ';;;', '', '15260', null, '', '1', 'F3233253 ', '1490324495', null, null);
INSERT INTO `think_deviceinfo` VALUES ('940890fd-1048-11e7-9a8b-d02788486e90', '10.130.2.211', '0', 'dddd', 'WinXp', 'ddd', 'dddd', 'ddd', ';;;', '', 'dddd', '1490284800', 'dddd', '0', 'F3233253 ', '1490328949', null, null);

-- ----------------------------
-- Table structure for think_service_apply
-- ----------------------------
DROP TABLE IF EXISTS `think_service_apply`;
CREATE TABLE `think_service_apply` (
  `id` varchar(50) NOT NULL COMMENT 'UUID編號',
  `ip` varchar(15) NOT NULL COMMENT 'IP地址',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '實體機 or 虛擬機',
  `size` text COMMENT '規格',
  `os` text NOT NULL COMMENT 'OS版本',
  `disk` varchar(50) NOT NULL COMMENT '硬盤',
  `ram` varchar(50) NOT NULL COMMENT '內存條',
  `cpuNum` varchar(50) NOT NULL COMMENT 'CPU',
  `remarks` longtext COMMENT '備註',
  `location` varchar(100) DEFAULT NULL COMMENT '所屬位置',
  `sn_Description` varchar(100) NOT NULL COMMENT '實體機編號 or 虛擬機名稱',
  `warranty` int(10) unsigned DEFAULT NULL COMMENT '维保期',
  `ilo` text COMMENT 'ILO(服务器远程管理)',
  `isValid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有效 Y-有效 N-无效',
  `creator` varchar(50) NOT NULL COMMENT '創建人',
  `createDate` int(10) unsigned NOT NULL COMMENT '創建時間',
  `modified` varchar(50) DEFAULT '' COMMENT '修改者',
  `modifyDate` int(10) unsigned DEFAULT NULL COMMENT '修改日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_service_apply
-- ----------------------------
INSERT INTO `think_service_apply` VALUES ('cb703165-0f90-11e7-9a8b-d02788486e90', '', '0', '', 'Red Hat7.2', 'Boot 500MB,VG(SWAP 8G,ROOT 50G-60G)', '8G', '4', ';ceshi;;', '', 'testComputer', null, '', '0', 'F3233253 ', '1490250015', 'F3233253 ', '1490267925');
INSERT INTO `think_service_apply` VALUES ('7c9facad-0f93-11e7-9a8b-d02788486e90', 'VM', '0', 'VM', 'Windows Service2016', 'C盤', '16G', '8', ';test;;', 'VM', 'testComputer1', null, 'VM', '1', 'F3233253 ', '1490251171', 'F3233253 ', '1490267937');
INSERT INTO `think_service_apply` VALUES ('69f0297c-0fa6-11e7-9a8b-d02788486e90', 'VM', '0', 'VM', 'Windows Service2012', 'C盤', '8G', '4', ';;;', 'VM', 'test1', null, 'VM', '1', 'F3233253 ', '1490259300', 'F3233253 ', '1490267902');
INSERT INTO `think_service_apply` VALUES ('b558993c-0fa6-11e7-9a8b-d02788486e90', 'VM', '0', 'VM', 'Windows Service2016', '500', '16', '2', ';;;', 'VM', 'test2', null, 'VM', '1', 'F3233253 ', '1490259427', null, null);
INSERT INTO `think_service_apply` VALUES ('4be1aaa3-0fa7-11e7-9a8b-d02788486e90', 'VM', '0', 'VM', 'Windows Service2012', 'C盤+D盤:600G', '1', '1', ';;;', 'VM', '1', null, 'VM', '1', 'F3233253 ', '1490259679', null, null);
INSERT INTO `think_service_apply` VALUES ('3b4db33d-103f-11e7-9a8b-d02788486e90', 'VM', '0', 'VM', 'Red Hat6.5', 'Boot 500MB,VG(SWAP 8G,ROOT 50G-60G),Home', '16G', '8', ';;;', 'VM', 'test_001', null, 'VM', '1', 'F3233253 ', '1490324935', null, null);

-- ----------------------------
-- Table structure for think_user
-- ----------------------------
DROP TABLE IF EXISTS `think_user`;
CREATE TABLE `think_user` (
  `admin_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员账号id',
  `account` varchar(64) NOT NULL COMMENT '登陆账号',
  `password` char(32) NOT NULL COMMENT '密码',
  `creat_time` int(10) unsigned DEFAULT NULL COMMENT '账号创建时间',
  `handler` varchar(64) DEFAULT NULL COMMENT '编辑者',
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_user
-- ----------------------------
INSERT INTO `think_user` VALUES ('1', 'admin', 'admin', null, null, null);
INSERT INTO `think_user` VALUES ('14', 'F3233253 ', '123456', '1488878560', 'admin', null);
