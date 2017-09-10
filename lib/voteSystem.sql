-- phpMyAdmin SQL Dump
-- version 4.0.10.11
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017-04-25 01:31:48
-- 服务器版本: 5.5.21-log
-- PHP 版本: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `votesystem`
--

-- --------------------------------------------------------

--
-- 表的结构 `activitys`
--

CREATE TABLE IF NOT EXISTS `activitys` (
  `activity_key` varchar(95) NOT NULL,
  `activity_name` varchar(50) NOT NULL COMMENT '活动名',
  `host` varchar(70) NOT NULL COMMENT '主办方',
  `intro` varchar(140) NOT NULL,
  `createtime` datetime NOT NULL COMMENT '创建时间',
  `starttime` datetime NOT NULL COMMENT '投票开始时间',
  `endtime` datetime NOT NULL COMMENT '投票结束时间',
  `creater` varchar(20) NOT NULL COMMENT '活动创建账号',
  `refreshcycle` int(3) NOT NULL DEFAULT '1' COMMENT '更新周期，单位：天,0表示不更新',
  `refreshballot` int(2) NOT NULL DEFAULT '1' COMMENT '更新票数,最多99',
  `platformlimit` int(1) NOT NULL DEFAULT '0' COMMENT '0：无限制；1：只能QQ；2：只能微信；3：只能微博；4：Q+微信；5：Q+微博；6：微信+微博；7：自定义用户',
  `rules` text NOT NULL COMMENT '规则说明',
  `ac_img` varchar(512) NOT NULL DEFAULT '../../dist/img/back.jpg' COMMENT '背景图地址',
  `ac_logo` varchar(512) NOT NULL DEFAULT '../../dist/img/logo.jpeg' COMMENT 'logo地址',
  PRIMARY KEY (`activity_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动信息表';

-- --------------------------------------------------------

--
-- 表的结构 `alluser`
--

CREATE TABLE IF NOT EXISTS `alluser` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `uniquekey` varchar(250) NOT NULL COMMENT '唯一标识',
  `name` varchar(20) NOT NULL COMMENT '昵称',
  `platform` varchar(3) NOT NULL COMMENT '所处平台',
  `activity_join` text NOT NULL COMMENT 'json形式储存，所有参加过的活动及其所投过的票数和票码记录',
  `area_ever` varchar(20) NOT NULL COMMENT 'json,所在过的地区',
  `use_ip` text NOT NULL COMMENT 'json，所用过的IP',
  `createtime` datetime NOT NULL COMMENT '添加时的时间',
  `use_mac` text NOT NULL COMMENT 'json，用过的mac地址',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniquekey` (`uniquekey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='三大平台所有透过票的用户信息' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `candidate`
--

CREATE TABLE IF NOT EXISTS `candidate` (
  `uniquekey` varchar(250) CHARACTER SET utf8 NOT NULL COMMENT '主键-唯一标识',
  `belong` varchar(128) CHARACTER SET utf8 NOT NULL COMMENT '主键-所属活动',
  `name` varchar(25) CHARACTER SET utf8 NOT NULL COMMENT '名称',
  `votes` int(6) NOT NULL DEFAULT '0' COMMENT '得票数',
  `contact` varchar(140) CHARACTER SET utf8 NOT NULL,
  `introduction` text CHARACTER SET utf8 NOT NULL COMMENT '简介',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '1：图片；2：视频；3：链接；4：音频',
  `imgurl` varchar(5000) CHARACTER SET utf8 NOT NULL COMMENT '图片路径',
  `videourl` text CHARACTER SET utf8 NOT NULL COMMENT '视频路径',
  `linkurl` text CHARACTER SET utf8 NOT NULL COMMENT '链接-链接',
  `linkcover` varchar(1500) CHARACTER SET utf8 NOT NULL DEFAULT '../../dist/img/photo1.png' COMMENT '链接-封面',
  `audiourl` text CHARACTER SET utf8 NOT NULL COMMENT '音频路径',
  PRIMARY KEY (`uniquekey`,`belong`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='导入的候选人表';

-- --------------------------------------------------------

--
-- 表的结构 `judge`
--

CREATE TABLE IF NOT EXISTS `judge` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `blindmsg` text NOT NULL COMMENT '盲化消息',
  `msg_sign` text NOT NULL COMMENT '消息-签名对',
  `belong` varchar(128) NOT NULL COMMENT '所属活动',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='裁决中心信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `key_pair`
--

CREATE TABLE IF NOT EXISTS `key_pair` (
  `register_public` text CHARACTER SET utf8 NOT NULL COMMENT '注册中心公钥',
  `register_private` text CHARACTER SET utf8 NOT NULL COMMENT '注册中心私钥',
  `count_public` text CHARACTER SET utf8 NOT NULL COMMENT '记票中心公钥',
  `count_private` text CHARACTER SET utf8 NOT NULL COMMENT '记票中心私钥'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='公钥私钥表';

-- --------------------------------------------------------

--
-- 表的结构 `record`
--

CREATE TABLE IF NOT EXISTS `record` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT '排序ID',
  `user_key` varchar(250) NOT NULL COMMENT '用户唯一性标识',
  `vote_username` varchar(20) NOT NULL COMMENT '用户昵称',
  `vote_plat` varchar(3) NOT NULL COMMENT '来源平台',
  `vote_area` varchar(20) NOT NULL,
  `vote_ip` varchar(20) NOT NULL,
  `votetime` datetime NOT NULL,
  `vote_mac` varchar(100) NOT NULL,
  `activity_key` varchar(512) NOT NULL COMMENT '所属活动',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- 表的结构 `voter`
--

CREATE TABLE IF NOT EXISTS `voter` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `uniquekey` varchar(250) NOT NULL,
  `nickname` varchar(25) NOT NULL COMMENT '用户昵称',
  `platform` varchar(3) NOT NULL COMMENT '账号所属平台:1:微信，2微博，3：QQ',
  `ballot` int(2) NOT NULL DEFAULT '0' COMMENT '选票数',
  `totalballot` int(4) NOT NULL DEFAULT '0' COMMENT '累计投票',
  `belong` varchar(128) NOT NULL COMMENT '所属活动',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='投票人信息例表' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `voter_import`
--

CREATE TABLE IF NOT EXISTS `voter_import` (
  `uniquekey` varchar(250) NOT NULL,
  `belong` varchar(128) NOT NULL,
  `nickname` varchar(25) NOT NULL COMMENT '昵称或姓名',
  `account` varchar(25) NOT NULL COMMENT '帐号',
  `password` varchar(25) NOT NULL COMMENT '密码',
  `ballot` int(2) NOT NULL DEFAULT '0' COMMENT '可用选票',
  `totalballot` int(4) NOT NULL DEFAULT '0' COMMENT '总计投票数',
  PRIMARY KEY (`uniquekey`,`belong`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='导入用户表';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
