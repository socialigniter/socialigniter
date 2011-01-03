ALTER TABLE  `categories` ADD  `details` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `description`
ALTER TABLE  `categories` CHANGE  `permission`  `access` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
ALTER TABLE  `categories` ADD  `updated_at` DATETIME NOT NULL AFTER  `created_at`
