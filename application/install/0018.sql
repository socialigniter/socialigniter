CREATE TABLE `uploads` (
  `upload_id` INT( 6 ) UNSIGNED NULL AUTO_INCREMENT PRIMARY KEY,
  `consumer_key` VARCHAR( 48 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_hash` VARCHAR( 48 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `uploaded_at` DATETIME NOT NULL
) ENGINE = INNODB;

ALTER TABLE  `connections` ADD  `created_at` DATETIME NOT NULL AFTER  `connection_id` ,
ADD  `updated_at` DATETIME NOT NULL AFTER  `created_at`