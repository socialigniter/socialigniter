ALTER TABLE  `connections` ADD  `site_id` INT( 6 ) NOT NULL AFTER  `connection_id`;
ALTER TABLE  `sites` DROP  `tagline` , DROP `description` , DROP `keywords`;
ALTER TABLE  `sites` ADD  `module` CHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `url`;
