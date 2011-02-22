DROP TABLE  `users_meta`

CREATE TABLE `users_meta` (
`user_meta_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`user_id` INT( 11 ) NOT NULL ,
`site_id` INT( 6 ) NOT NULL ,
`module` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`meta` CHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE `users` ADD `gravatar` VARCHAR( 48 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `email`;
ALTER TABLE `users` ADD `name` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `email`;
ALTER TABLE `users` ADD `image` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `name`;
ALTER TABLE `users` ADD `time_zone` VARCHAR( 8 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `image`;
ALTER TABLE `users` ADD `privacy` INT( 1 ) NULL AFTER  `time_zone`;
ALTER TABLE `users` ADD `consumer_key` VARCHAR( 48 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `privacy`;
ALTER TABLE `users` ADD `consumer_secret` VARCHAR( 48 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `consumer_key`;
ALTER TABLE `users` ADD `token` VARCHAR( 48 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `consumer_secret`;
ALTER TABLE `users` ADD `token_secret` VARCHAR( 48 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `token`;