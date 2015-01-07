<?php exit; ?>

-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 建立日期: Jan 07, 2015, 03:42 PM
-- 伺服器版本: 5.1.69
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 資料庫: `gear`
--

-- --------------------------------------------------------

--
-- 資料表格式： `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `blog_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL COMMENT '1=啟用, 0=停用, 9=刪除',
  `public_status` tinyint(3) unsigned NOT NULL COMMENT '1=公開, 0=不公開',
  `name` varchar(60) NOT NULL,
  `keyword` varchar(20) DEFAULT NULL COMMENT '特殊鍵值 英文或數字, 可混合',
  `normalized_search` text NOT NULL COMMENT '搜尋內容',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `properties` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `blog_id` (`blog_id`),
  KEY `blog_id_and_status` (`blog_id`,`status`),
  KEY `blog_id_and_keyword` (`blog_id`,`keyword`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `article_items`
--

CREATE TABLE IF NOT EXISTS `article_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL COMMENT '1=文章內容',
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `article_id_and_type` (`article_id`,`type`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `article_search_tags`
--

CREATE TABLE IF NOT EXISTS `article_search_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `blog_id` int(10) unsigned NOT NULL,
  `article_id` int(10) unsigned NOT NULL,
  `article_status` tinyint(3) unsigned NOT NULL,
  `article_public_status` tinyint(3) unsigned NOT NULL,
  `article_name` varchar(100) NOT NULL,
  `article_tag_id` int(10) unsigned NOT NULL,
  `article_tag_name` varchar(20) NOT NULL,
  `article_tag_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `blogid_and_status_and_searchtagname` (`blog_id`,`article_status`,`article_tag_name`),
  KEY `blogid_and_articletagid_and_status_and_publicstatus` (`blog_id`,`article_tag_id`,`article_status`,`article_public_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `article_tags`
--

CREATE TABLE IF NOT EXISTS `article_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `keyword` varchar(20) NOT NULL,
  `num_enable_public_articles` smallint(5) unsigned NOT NULL COMMENT '啟用 並且 公開 的文章數量',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `properties` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`),
  KEY `user_id` (`user_id`),
  KEY `user_id_and_status` (`user_id`,`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
-- 資料表格式： `country_states`
--

CREATE TABLE IF NOT EXISTS `country_states` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_code` char(2) NOT NULL,
  `state_code` char(2) NOT NULL,
  `state` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `properties` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_code_and_state_code` (`country_code`,`state_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `country_state_cities`
--

CREATE TABLE IF NOT EXISTS `country_state_cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_code` char(2) NOT NULL,
  `state_code` char(2) NOT NULL,
  `city` varchar(200) NOT NULL,
  `city_flat` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `properties` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_code_and_state_code_and_city` (`country_code`,`state_code`,`city`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `country_state_city_zip_codes`
--

CREATE TABLE IF NOT EXISTS `country_state_city_zip_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_code` char(2) NOT NULL,
  `state_code` char(2) NOT NULL,
  `city` varchar(200) NOT NULL,
  `zip_code` char(5) NOT NULL,
  `zip_code_type` tinyint(4) NOT NULL,
  `location_type` tinyint(4) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `x_axis` float NOT NULL,
  `y_axis` float NOT NULL,
  `z_axis` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
-- 資料表格式： `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(80) NOT NULL,
  `password` char(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) unsigned NOT NULL DEFAULT '9' COMMENT '1=啟用, 0=停用, 9=刪除',
  `email_status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '1=已認證, 0=未認證',
  `name` varchar(60) NOT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `properties` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nickname` (`nickname`),
  KEY `email_and_password` (`email`,`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表格式： `member_search_facebook`
--

CREATE TABLE IF NOT EXISTS `member_search_facebook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facebook_id` varchar(20) NOT NULL,
  `email` varchar(80) NOT NULL,
  `name` varchar(80) NOT NULL,
  `gender` tinyint(4) unsigned NOT NULL DEFAULT '3' COMMENT '1=男, 2=女, 3=未知',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `facebook_id` (`facebook_id`)
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
-- 資料表格式： `user_logs`
--

CREATE TABLE IF NOT EXISTS `user_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `type` varchar(16) NOT NULL,
  `content` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
