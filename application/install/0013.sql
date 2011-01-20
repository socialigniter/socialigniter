ALTER TABLE  `relationships` ADD  `type` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `user_id`
ALTER TABLE  `relationships` ADD  `type_sub` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `type`
ALTER TABLE  `comments` ADD  `owner_id` INT( 11 ) NULL AFTER  `content_id`
