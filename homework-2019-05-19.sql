-- ----------------------------
-- 日期：2019-05-19 23:20:13
-- MySQL - 5.1.73 : Database - homework
-- ----------------------------

CREATE DATAbase IF NOT EXISTS `homework` DEFAULT CHARACTER SET utf8 ;

USE `homework`;

-- ----------------------------
-- Table structure for `供货商`
-- ----------------------------

DROP TABLE IF EXISTS `供货商`;

CREATE TABLE `供货商` (
  `number` varchar(3) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `lianxiren` varchar(50) NOT NULL,
  `lianxi_way` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Data for the table `供货商`
-- ----------------------------

INSERT INTO `供货商` VALUES ('001', '哇哈哈', '北京', 'aaa', '123456789');
INSERT INTO `供货商` VALUES ('002', '桃李', '北京', 'qqq', '1111');

-- ----------------------------
-- Table structure for `商品`
-- ----------------------------

DROP TABLE IF EXISTS `商品`;

CREATE TABLE `商品` (
  `shangpin_num` varchar(5) NOT NULL,
  `shangpin_name` varchar(30) NOT NULL,
  `shangpin_type` varchar(15) NOT NULL,
  `shangpin_price` varchar(10) NOT NULL,
  `shangpin_guige` varchar(10) NOT NULL,
  `kucun_count` int(6) NOT NULL,
  `suppler_num` varchar(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Data for the table `商品`
-- ----------------------------

INSERT INTO `商品` VALUES ('001', '矿泉水', '饮料', '2', '500ml', '50', '001');
INSERT INTO `商品` VALUES ('002', '面包', '食品', '5', '200g', '46', '002');
INSERT INTO `商品` VALUES ('', '', '', '', '', '0', '');
INSERT INTO `商品` VALUES ('003', '咖啡', '饮品', '10', '200g', '220', '003');

-- ----------------------------
-- Table structure for `进货`
-- ----------------------------

DROP TABLE IF EXISTS `进货`;

CREATE TABLE `进货` (
  `huodan_num` varchar(8) NOT NULL,
  `supplier_num` varchar(3) NOT NULL,
  `shangpin_num` varchar(5) NOT NULL,
  `price` varchar(10) NOT NULL,
  `count` int(11) NOT NULL,
  `date` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Data for the table `进货`
-- ----------------------------

INSERT INTO `进货` VALUES ('001', '001', '30', '3', '80', '2019/5/19');
INSERT INTO `进货` VALUES ('002', '002', '50', '2', '80', '2019/5/19');
INSERT INTO `进货` VALUES ('003', '003', '003', '10', '100', '2019/5/19');
INSERT INTO `进货` VALUES ('003', '003', '003', '10', '100', '2019/5/19');

-- ----------------------------
-- Table structure for `销售信息表`
-- ----------------------------

DROP TABLE IF EXISTS `销售信息表`;

CREATE TABLE `销售信息表` (
  `fapiao_num` varchar(8) NOT NULL,
  `shangpin_num` varchar(5) NOT NULL,
  `shangpin_name` varchar(30) NOT NULL,
  `xiaoshou_price` varchar(10) NOT NULL,
  `xiaoshou_count` int(11) NOT NULL,
  `pay_money` varchar(40) NOT NULL,
  `date` text NOT NULL,
  `xiaoshouyuan` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Data for the table `销售信息表`
-- ----------------------------

INSERT INTO `销售信息表` VALUES ('004', '002', '面包', '5', '1', '5', '2019/05/19', 'sunsec');
INSERT INTO `销售信息表` VALUES ('002', '002', '面包', '5', '1', '5', '2019/05/19', 'sunsec');
INSERT INTO `销售信息表` VALUES ('002', '002', '面包', '5', '1', '5', '2019/05/19', 'sunsec');
INSERT INTO `销售信息表` VALUES ('001', '002', '面包', '5', '1', '5', '2019/05/19', 'sunsec');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Data for the table `users`
-- ----------------------------

INSERT INTO `users` VALUES ('admin', 'admin', 'administrator');
INSERT INTO `users` VALUES ('guest', 'guest', 'guest');

