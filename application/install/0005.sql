ALTER TABLE  `content` CHANGE  `parent_id`  `parent_id` INT( 11 ) NULL DEFAULT  '0',
CHANGE  `module`  `module` CHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
CHANGE  `type`  `type` CHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
CHANGE  `source`  `source` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
CHANGE  `viewed`  `viewed` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
CHANGE  `approval`  `approval` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL

ALTER TABLE  `activity` CHANGE  `site_id`  `site_id` INT( 11 ) NULL DEFAULT NULL ,
CHANGE  `user_id`  `user_id` INT( 11 ) NULL DEFAULT NULL ,
CHANGE  `verb`  `verb` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE  `module`  `module` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE  `type`  `type` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
CHANGE  `content_id`  `content_id` INT( 11 ) NULL DEFAULT NULL ,
CHANGE  `data`  `data` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE  `created_at`  `created_at` DATETIME NULL DEFAULT NULL