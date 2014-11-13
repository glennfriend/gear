-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 建立日期: Nov 12, 2014, 03:33 PM
-- 伺服器版本: 5.1.69
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 資料庫: `gear`
--

-- --------------------------------------------------------

--
-- 資料表格式： `configs`
--

CREATE TABLE IF NOT EXISTS `configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` varchar(100) NOT NULL,
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1=顯示 0=不顯示 , 無論如何都啟用',
  `seq_no` smallint(6) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '群組值只是分區域, 並無 unique 的問題',
  `properties` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_group_display_seq_no` (`group`,`display`,`seq_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `form_contents`
--

CREATE TABLE IF NOT EXISTS `form_contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `form_page_id` int(10) unsigned NOT NULL,
  `contents` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `custom_search` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_and_form_page_id` (`user_id`,`form_page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `form_pages`
--

CREATE TABLE IF NOT EXISTS `form_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `keyword` varchar(60) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `time_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `properties` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `storage_city_ips`
--

CREATE TABLE IF NOT EXISTS `storage_city_ips` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_from` int(11) NOT NULL COMMENT 'First IP address in netblock',
  `ip_to` int(11) NOT NULL COMMENT 'Last IP address in netblock',
  `country_code` char(2) NOT NULL,
  `region_name` varchar(128) NOT NULL,
  `city_name` varchar(128) NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_ip_from_and_ip_to` (`ip_from`,`ip_to`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(16) NOT NULL,
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `role_ids` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `properties` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`),
  KEY `account_password_index` (`account`,`password`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
