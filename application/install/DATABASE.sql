SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE `activity` (
  `activity_id` int(32) NOT NULL AUTO_INCREMENT,
  `site_id` int(6) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `verb` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `module` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,  
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `site_id` int(6) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `access` char(1) DEFAULT NULL,
  `module` char(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` char(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(128) DEFAULT NULL,
  `category_url` varchar(128) DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `contents_count` int(6) DEFAULT NULL,
  `created_at` datetime DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(6) NOT NULL,
  `reply_to_id` int(11) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `module` char(16) DEFAULT NULL,
  `type` char(16) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `geo_lat` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `geo_long` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `viewed` char(1) NOT NULL,
  `approval` char(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `connections` (
  `connection_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(6) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `module` char(32) NOT NULL,
  `type` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `connection_user_id` int(64) DEFAULT NULL,
  `connection_username` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_one` varchar(255) DEFAULT NULL,
  `auth_two` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`connection_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content` (
  `content_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(6) DEFAULT '0',
  `parent_id` int(11) DEFAULT '0',
  `category_id` int(3) DEFAULT '0',
  `module` char(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` char(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(4) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `title_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `details` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `access` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments_allow` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'Y',
  `comments_count` int(6) DEFAULT NULL,
  `geo_lat` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `geo_long` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `viewed` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `approval` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `content` VALUES(NULL, 1, 0, 0, 'pages', 'page', '', 0, 1, 'Welcome', 'welcome', 'Welcome to my website. Word word!', 'index', 'E', 'Y', 3, NULL, NULL, '', '', 'P', '2010-07-17 00:00:00', '2010-12-06 00:45:45');
INSERT INTO `content` VALUES(NULL, 1, 0, 0, 'pages', 'page', '', 2, 1, 'Contact', 'contact', 'Please contact us', 'site', NULL, 'N', 0, NULL, NULL, '', '', 'P', '2010-07-17 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `content` VALUES(NULL, 1, 0, 0, 'pages', 'page', '', 1, 1, 'About', 'about', 'Write what your website is about here!', 'site', 'S', 'A', 0, NULL, NULL, '', '', 'P', '2010-07-17 00:00:00', '2010-12-06 00:59:42');

CREATE TABLE `content_meta` (
  `content_meta_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(6) NOT NULL,
  `content_id` int(11) NOT NULL,
  `meta` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_At` datetime NOT NULL,
  PRIMARY KEY (`content_meta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE `places` (
  `place_id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) DEFAULT NULL,
  `address` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `locality` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `region` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`place_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE `oauth_server_nonce` (
  `osn_id` int(11) NOT NULL AUTO_INCREMENT,
  `osn_consumer_key` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `osn_token` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `osn_timestamp` bigint(20) NOT NULL,
  `osn_nonce` varchar(80) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`osn_id`),
  UNIQUE KEY `osn_consumer_key` (`osn_consumer_key`,`osn_token`,`osn_timestamp`,`osn_nonce`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `oauth_server_registry` (
  `osr_id` int(11) NOT NULL AUTO_INCREMENT,
  `osr_usa_id_ref` int(11) DEFAULT NULL,
  `osr_consumer_key` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `osr_consumer_secret` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `osr_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `osr_status` varchar(16) NOT NULL,
  `osr_requester_name` varchar(64) NOT NULL,
  `osr_requester_email` varchar(64) NOT NULL,
  `osr_callback_uri` varchar(255) NOT NULL,
  `osr_application_uri` varchar(255) NOT NULL,
  `osr_application_title` varchar(80) NOT NULL,
  `osr_application_descr` text NOT NULL,
  `osr_application_notes` text NOT NULL,
  `osr_application_type` varchar(20) NOT NULL,
  `osr_application_commercial` tinyint(1) NOT NULL DEFAULT '0',
  `osr_issue_date` datetime NOT NULL,
  `osr_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`osr_id`),
  UNIQUE KEY `osr_consumer_key` (`osr_consumer_key`),
  KEY `osr_usa_id_ref` (`osr_usa_id_ref`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `oauth_server_token` (
  `ost_id` int(11) NOT NULL AUTO_INCREMENT,
  `ost_osr_id_ref` int(11) NOT NULL,
  `ost_usa_id_ref` int(11) NOT NULL,
  `ost_token` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ost_token_secret` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ost_token_type` enum('request','access') DEFAULT NULL,
  `ost_authorized` tinyint(1) NOT NULL DEFAULT '0',
  `ost_referrer_host` varchar(128) NOT NULL DEFAULT '',
  `ost_token_ttl` datetime NOT NULL DEFAULT '9999-12-31 00:00:00',
  `ost_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ost_verifier` char(10) DEFAULT NULL,
  `ost_callback_url` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`ost_id`),
  UNIQUE KEY `ost_token` (`ost_token`),
  KEY `ost_osr_id_ref` (`ost_osr_id_ref`),
  KEY `ost_token_ttl` (`ost_token_ttl`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(6) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `type` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rating` char(3) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`rating_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `relationships` (
  `relationship_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(6) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `type` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`relationship_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE `settings` (
  `settings_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(6) NOT NULL,
  `module` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `setting` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO `settings` VALUES(NULL, 1, 'site', 'title', 'Awesome Website');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'tagline', 'Where I Post All My Awesome Things');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'keywords', 'awesome, things, pictures, videos, poems, watermelons, cats, ninjas');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'description', 'This is my awesome website where I post awesome stuff. Some of my favorite things are ninjas, watermelons, and cats');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'url', 'http://domainname.com');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'languages_default', 'en');
INSERT INTO `settings` VALUES(NULL, 1, 'site', 'admin_email', 'you@email.com');
INSERT INTO `settings` VALUES(NULL, 1, 'themes', 'site_theme', 'site_default');
INSERT INTO `settings` VALUES(NULL, 1, 'themes', 'dashboard_theme', 'dashboard_default');
INSERT INTO `settings` VALUES(NULL, 1, 'themes', 'mobile_theme', 'mobile_default');
INSERT INTO `settings` VALUES(NULL, 1, 'widgets', 'sidebar', '{"module":"users","name":"Login","method":"view","path":"login","enabled":"TRUE","order":"1"}');
INSERT INTO `settings` VALUES(NULL, 1, 'widgets', 'sidebar', '{"module":"text","name":"Text","method":"text","path":"","enabled":"TRUE","order":"2","content":"<h2>Hello</h2><p>Thanks for stopping by. We absolutely love visitors. Take off your digital shoes, relax, and feast your eyes on our pretty pixels!</p>"}');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'email_protocol', 'mail');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'smtp_host', '');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'smtp_user', '');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'smtp_pass', '');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'smtp_port', '');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'mobile_enabled', 'FALSE');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'mobile_module', '--select--');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'google_webmaster', '');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'google_analytics', '');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'bing_webmaster', '');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'gravatar_enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'bitly_enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'bitly_login', '');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'bitly_api_key', '');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'bitly_domain', 'bit.ly');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'akismet_key', 'dc0465ba152f');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'recaptcha_public', '6Lch7LwSAAAAACP2t2e1qpIQ9Cz7AsvXRfJf1yW_');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'recaptcha_private', '6Lch7LwSAAAAAJvERNehdFPPPZ5TQjd1DgjJRTmK');
INSERT INTO `settings` VALUES(NULL, 1, 'services', 'recaptcha_theme', 'white');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'reply', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'reply_level', '2');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'comments_date_style', 'SIMPLE_TIME');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'email_signup', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'email_replies', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'akismet', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'recaptcha', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'comments', 'date_style', 'ELAPSED');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'create_permission', '3');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'publish_permission', '2');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'manage_permission', '1');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'ratings_allow', 'no');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'comments_per_page', '5');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'comments_allow', 'no');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'tags_display', 'no');
INSERT INTO `settings` VALUES(NULL, 1, 'ratings', 'enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'ratings', 'rate_type', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'create_permission', '3');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'publish_permission', '2');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'manage_permission', '1');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'ratings_allow', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'tags_display', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'comments_allow', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'pages', 'comments_per_page', '10');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'public_timeline', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'status_length', '140');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'date_style', 'ELAPSED');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'description_length', '110');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'share', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'like', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'comments_allow', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'home', 'comments_per_page', '2');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'signup', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'signup_recaptcha', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'login', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'login_recaptcha', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'create_permission', '2');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'manage_permission', '2');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'profile', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'profile_activity', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'profile_relationships', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'profile_content', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'message_allow', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'message_recaptcha', '5');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'comments_allow', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'comments_per_page', '10');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_sizes_large', 'yes');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_sizes_medium', 'yes');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_sizes_small', 'yes');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_large_width', '275');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_large_height', '175');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_medium_width', '48');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_medium_height', '48');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_small_width', '45');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_small_height', '25');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_formats', 'gif|jpg|jpeg|png');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_max_size', '25600');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_full_width', '750');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_full_height', '750');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_sizes_full', 'yes');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_folder', 'uploads/profiles/');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_max_dimensions', '3000');
INSERT INTO `settings` VALUES(NULL, 1, 'users', 'images_sizes_original', 'yes');

CREATE TABLE `sites` (
  `site_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module` char(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` char(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `favicon` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

INSERT INTO `sites` VALUES(1, 'http://localhost/', 'site', 'default', 'Social-Igniter', NULL);

CREATE TABLE `tags` (
  `tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(128) DEFAULT NULL,
  `tag_url` varchar(128) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `tags_link` (
  `tag_link_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`tag_link_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `taxonomy` (
  `taxonomy_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `taxonomy` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `count` int(5) NOT NULL,
  PRIMARY KEY (`taxonomy_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `uploads` (
  `upload_id` INT( 6 ) UNSIGNED NULL AUTO_INCREMENT PRIMARY KEY ,
  `consumer_key` VARCHAR( 48 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
  `file_hash` VARCHAR( 48 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,  
  `uploaded_at` DATETIME NOT NULL
) ENGINE = INNODB;

CREATE TABLE `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_level_id` tinyint(3) unsigned DEFAULT NULL,
  `ip_address` char(16) DEFAULT NULL,
  `username` varchar(64) DEFAULT NULL,
  `password` varchar(48) DEFAULT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `gravatar` varchar(48) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_zone` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `privacy` int(1) DEFAULT NULL,
  `language` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `geo_enabled` tinyint(1) DEFAULT NULL,
  `consumer_key` varchar(48) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `consumer_secret` varchar(48) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(48) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_secret` varchar(48) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `active` int(1) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(16) unsigned DEFAULT NULL,
  `last_login` int(16) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `users_level` (
  `user_level_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `level` char(20) NOT NULL,
  `level_name` char(64) NOT NULL,
  `description` char(255) NOT NULL,
  PRIMARY KEY (`user_level_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `users_level` VALUES(1, 'superadmin', 'Super Admin', 'Super Admins are the head honchos who have power to do anything they want on your install of Social Igniter');
INSERT INTO `users_level` VALUES(2, 'admin', 'Admin', 'Admins can do most things, not all, but most things needed on a site');
INSERT INTO `users_level` VALUES(3, 'superuser', 'Super User', 'Supers Users help keep the ship on course, they do some things, but not all');
INSERT INTO `users_level` VALUES(4, 'user', 'User', 'Users are just regular Joes or Joesephines. They use your application as it is intended for the general public');

CREATE TABLE  `users_meta` (
  `user_meta_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `user_id` INT( 11 ) NOT NULL ,
  `site_id` INT( 6 ) NOT NULL ,
  `module` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL ,
  `meta` CHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL ,
  `value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL ,
  `created_at` datetime DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE `users_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `oauth_server_token`
  ADD CONSTRAINT `oauth_server_token_ibfk_1` FOREIGN KEY (`ost_osr_id_ref`) REFERENCES `oauth_server_registry` (`osr_id`) ON DELETE CASCADE ON UPDATE CASCADE;
