ALTER TABLE `ratings` ADD `ip_address` CHAR(16) NULL AFTER  `rating`;
ALTER TABLE `ratings` CHANGE `rating` `rating` CHAR(32)  NULL  DEFAULT NULL;
