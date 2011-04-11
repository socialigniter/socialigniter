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

INSERT INTO `settings` VALUES(NULL, 1, 'places', 'enabled', 'TRUE');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'ratings_allow', 'no');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'comments_per_page', '5');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'comments_allow', 'no');
INSERT INTO `settings` VALUES(NULL, 1, 'places', 'tags_display', 'no');