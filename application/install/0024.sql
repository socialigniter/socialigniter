ALTER TABLE  `content` CHANGE  `details`  `details` VARCHAR( 512 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE  `content` ADD  `canonical` VARCHAR( 512 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `details`;
ALTER TABLE  `uploads` ADD  `user_id` INT( 11 ) NOT NULL AFTER  `upload_id`;
