INSERT INTO `settings` VALUES(NULL, 1, 'design', 'site_logo', '');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'header_image', '');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'header_position', 'left top');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'header_repeat', 'repeat-x');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'header_color', 'ffffff');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'background_image', '');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'background_position', 'left top');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'background_repeat', 'repeat-x');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'background_color', 'ffffff');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'link_color_normal', '0066CC');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'link_color_visited', '0066CC');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'link_color_hover', 'ff3300');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'header_link_color_normal', '0066CC');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'header_link_color_visited', '0066CC');
INSERT INTO `settings` VALUES(NULL, 1, 'design', 'header_link_color_hover', 'ff3300');

ALTER TABLE `categories` ADD `content_id` INT(11) NULL AFTER  `parent_id`;