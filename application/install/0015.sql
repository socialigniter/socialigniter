ALTER TABLE  `connections` ADD  `site_id` INT( 6 ) NOT NULL AFTER  `connection_id`;
ALTER TABLE  `sites` DROP  `tagline` , DROP `description` , DROP `keywords`;