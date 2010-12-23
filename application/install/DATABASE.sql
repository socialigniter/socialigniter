-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 18, 2010 at 11:53 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `social-igniter`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `access_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `granted_id` int(11) DEFAULT NULL,
  `type` char(16) NOT NULL,
  `level_id` char(1) NOT NULL,
  PRIMARY KEY (`access_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `access`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activity_id` int(32) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `verb` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `module` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,  
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`activity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_sub_id` int(11) DEFAULT NULL,
  `site_id` int(11) DEFAULT NULL,
  `permission` char(32) DEFAULT NULL,
  `module` char(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(128) DEFAULT NULL,
  `category_url` varchar(128) DEFAULT NULL,
  `created_at` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `categories`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(6) NOT NULL,
  `reply_to_id` int(11) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `module` char(16) DEFAULT NULL,
  `type` char(16) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `geo_lat` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `geo_long` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `geo_accuracy` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `viewed` char(1) NOT NULL,
  `approval` char(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- Dumping data for table `comments`
--

-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE IF NOT EXISTS `connections` (
  `connection_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) unsigned default NULL,
  `module` char(32) NOT NULL,
  `type` char(32) character set utf8 collate utf8_unicode_ci default NULL,
  `connection_user_id` int(64) default NULL,
  `connection_username` varchar(128) character set utf8 collate utf8_unicode_ci default NULL,
  `auth_one` varchar(255) default NULL,
  `auth_two` varchar(255) default NULL,
  PRIMARY KEY  (`connection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `content_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(6) DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(3) DEFAULT '0',
  `module` char(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` char(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(4) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `title_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `details` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `access` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments_allow` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'Y',
  `comments_count` int(11) DEFAULT NULL,
  `geo_lat` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `geo_long` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `geo_accuracy` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `content`
--

INSERT INTO `content` VALUES(1, 1, 0, 0, 'pages', 'page', '', 0, 1, 'Welcome', 'welcome', 'Welcome to my website. Word word!', 'index', 'S', 'A', 0, NULL, NULL, NULL, 'P', '2010-07-17 00:00:00', '2010-08-18 23:50:47');
INSERT INTO `content` VALUES(2, 1, 0, 0, 'pages', 'page', '', 2, 1, 'Contact', 'contact', 'Please contact us', 'site', NULL, 'N', 0, NULL, NULL, NULL, 'P', '2010-07-17 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `content` VALUES(3, 1, 0, 0, 'pages', 'page', '', 1, 1, 'About', 'about', 'Write what your website is about here!', 'site', 'S', 'A', 0, NULL, NULL, NULL, 'P', '2010-07-17 00:00:00', '2010-08-18 23:50:56');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(6) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `type` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rating` char(3) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`rating_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- Dumping data for table `ratings`
--


-- --------------------------------------------------------

--
-- Table structure for table `relationships`
--

CREATE TABLE `relationships` (
  `relationship_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`relationship_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;

--
-- Dumping data for table `relationships`
--


-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settings_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(6) NOT NULL,
  `module` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `setting` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `settings`
--
INSERT INTO `settings` VALUES(NULL, 1, 'theme', 'site', 'site_default');
INSERT INTO `settings` VALUES(NULL, 1, 'theme', 'dashboard', 'dashboard_default');
INSERT INTO `settings` VALUES(NULL, 1, 'theme', 'mobile', 'mobile_default');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'google_webmaster', 'f8JKVGUrqq-ZBS4oz44kH5gbKMRKjSoSJKDK-_sfCBg');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'google_analytics', '');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'bing_webmaster', '');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'gravatar_enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'bitly_enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'bitly_login', '');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'bitly_api_key', '');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'bitly_domain', '');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'akismet_key', '');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'recaptcha_public', '');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'recaptcha_private', '');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'recaptcha_theme', 'white');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'reply', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'reply_level', '2');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'comments_date_style', 'SIMPLE_TIME');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'email_signup', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'email_replies', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'akismet', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'recaptcha', 'FALSE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'date_style', 'ELAPSED');
INSERT INTO `settings` VALUES(NULL, 1, 'ratings', 'enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'ratings', 'rate_type', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'ratings_allow', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'tags_display', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'comments_allow', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'comments_per_page', '10');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'public_timeline', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'date_style', 'ELAPSED');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'description_length', '110');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'share', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'like', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'comments_allow', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'comments_per_page', '2');

INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_sizes_large', 'yes');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_sizes_medium', 'yes');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_sizes_small', 'yes');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_large_width', '800');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_large_height', '600');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_medium_width', '600');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_medium_height', '400');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_small_width', '300');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_small_height', '200');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_formats', 'gif|jpg|jpeg|png');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_max_size', '25600');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'signup', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'signup_recaptcha', 'FALSE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'login', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'login_recaptcha', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'profile', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'profile_activity', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'profile_relationships', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'profile_content', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'message_allow', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'message_recaptcha', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'comments_allow', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'comments_per_page', '10');

-- --------------------------------------------------------


--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `site_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` char(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tagline` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `favicon` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` VALUES(1, 'http://social-igniter.com', 'default', 'Social-Igniter', 'A Really Simple Open Source Social Web Application Template', 'Social-Igniter is a really simple open source social web application template', 'social, web application, open source, codeigniter, php', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(128) DEFAULT NULL,
  `tag_url` varchar(128) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- Dumping data for table `tags`
--

-- --------------------------------------------------------

--
-- Table structure for table `tags_link`
--

CREATE TABLE `tags_link` (
  `tag_link_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`tag_link_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- Dumping data for table `tags_link`
--


-- --------------------------------------------------------

--
-- Table structure for table `taxonomy`
--

CREATE TABLE `taxonomy` (
  `taxonomy_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `taxonomy` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `count` int(5) NOT NULL,
  PRIMARY KEY (`taxonomy_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- Dumping data for table `taxonomy`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_level_id` tinyint(3) unsigned DEFAULT NULL,
  `ip_address` char(16) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(48) DEFAULT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `active` int(1) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(16) unsigned DEFAULT NULL,
  `last_login` int(16) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- Dumping data for table `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `users_level`
--

CREATE TABLE `users_level` (
  `user_level_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`user_level_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users_level`
--

INSERT INTO `users_level` VALUES(1, 'superadmin', 'Super Admin');
INSERT INTO `users_level` VALUES(2, 'admin', 'Admin');
INSERT INTO `users_level` VALUES(3, 'superuser', 'Super User');
INSERT INTO `users_level` VALUES(4, 'user', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `users_meta`
--

CREATE TABLE `users_meta` (
  `user_meta_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `company` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_verify` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_active` tinyint(1) DEFAULT NULL,
  `phone_search` tinyint(1) DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `home_base` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_zone` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `geo_enabled` tinyint(1) DEFAULT NULL,
  `privacy` tinyint(1) DEFAULT NULL,
  `utc_offset` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_meta_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- Dumping data for table `users_meta`
--
-- --------------------------------------------------------

--
-- Table structure for table `users_sessions`
--

CREATE TABLE `users_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_sessions`
--


# OAUTH TABLES TEMPORARY UNTIL MERGED INTO Social  Igniter
CREATE TABLE IF NOT EXISTS oauth_server_registry (
    osr_id                      int(11) not null auto_increment,
    osr_usa_id_ref              int(11),
    osr_consumer_key            varchar(64) binary not null,
    osr_consumer_secret         varchar(64) binary not null,
    osr_enabled                 tinyint(1) not null default '1',
    osr_status                  varchar(16) not null,
    osr_requester_name          varchar(64) not null,
    osr_requester_email         varchar(64) not null,
    osr_callback_uri            varchar(255) not null,
    osr_application_uri         varchar(255) not null,
    osr_application_title       varchar(80) not null,
    osr_application_descr       text not null,
    osr_application_notes       text not null,
    osr_application_type        varchar(20) not null,
    osr_application_commercial  tinyint(1) not null default '0',
    osr_issue_date              datetime not null,
    osr_timestamp               timestamp not null default current_timestamp,

    primary key (osr_id),
    unique key (osr_consumer_key),
    key (osr_usa_id_ref)
) engine=InnoDB default charset=utf8;

#--SPLIT--
CREATE TABLE IF NOT EXISTS oauth_server_nonce (
    osn_id                  int(11) not null auto_increment,
    osn_consumer_key        varchar(64) binary not null,
    osn_token               varchar(64) binary not null,
    osn_timestamp           bigint not null,
    osn_nonce               varchar(80) binary not null,

    primary key (osn_id),
    unique key (osn_consumer_key, osn_token, osn_timestamp, osn_nonce)
) engine=InnoDB default charset=utf8;

#--SPLIT--
CREATE TABLE IF NOT EXISTS oauth_server_token (
    ost_id                  int(11) not null auto_increment,
    ost_osr_id_ref          int(11) not null,
    ost_usa_id_ref          int(11) not null,
    ost_token               varchar(64) binary not null,
    ost_token_secret        varchar(64) binary not null,
    ost_token_type          enum('request','access'),
    ost_authorized          tinyint(1) not null default '0',
	ost_referrer_host       varchar(128) not null default '',
	ost_token_ttl           datetime not null default '9999-12-31',
    ost_timestamp           timestamp not null default current_timestamp,
    ost_verifier            char(10),
    ost_callback_url        varchar(512),

	primary key (ost_id),
    unique key (ost_token),
    key (ost_osr_id_ref),
	key (ost_token_ttl),

	foreign key (ost_osr_id_ref) references oauth_server_registry (osr_id)
        on update cascade
        on delete cascade
) engine=InnoDB default charset=utf8;


