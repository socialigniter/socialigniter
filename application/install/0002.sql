ALTER TABLE  `categories` CHANGE  `category_sub_id`  `parent_id` INT( 11 ) NULL DEFAULT NULL
ALTER TABLE  `categories` ADD  `type` CHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `module`
ALTER TABLE  `categories` CHANGE  `module`  `module` CHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
