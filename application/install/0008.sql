CREATE TABLE  `social-igniter`.`content_meta` (
  `content_meta_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `site_id` INT( 6 ) NOT NULL ,
  `content_id` INT( 1 ) NOT NULL ,
  `key` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
  `value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime DEFAULT '0000-00-00 00:00:00',
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE  `categories` ADD  `user_id` INT( 11 ) NULL AFTER  `site_id`
ALTER TABLE  `categories` ADD  `description` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `category_url`