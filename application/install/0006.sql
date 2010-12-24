ALTER TABLE  `users_meta` CHANGE  `image`  `image` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL

INSERT INTO  `social-igniter`.`settings` (`settings_id` ,`site_id` ,`module` ,`setting` ,`value`)
VALUES (NULL ,  '1',  'users',  'images_sizes_full',  'yes');

INSERT INTO  `social-igniter`.`settings` (`settings_id` ,`site_id` ,`module` ,`setting` ,`value`) 
VALUES (NULL ,  '1',  'users',  'images_full_width',  '750');

INSERT INTO  `social-igniter`.`settings` (`settings_id` ,`site_id` ,`module` ,`setting` ,`value`) 
VALUES (NULL ,  '1',  'users',  'images_full_height',  '750');

INSERT INTO  `social-igniter`.`settings` (`settings_id` ,`site_id` ,`module` ,`setting` ,`value`)
VALUES (NULL ,  '1',  'users',  'images_folder',  'media/profiles/');

INSERT INTO  `social-igniter`.`settings` (`settings_id` ,`site_id` ,`module` ,`setting` ,`value`)
VALUES (NULL ,  '1',  'users',  'images_max_dimensions',  '3000');

INSERT INTO  `social-igniter`.`settings` (`settings_id` ,`site_id` ,`module` ,`setting` ,`value`)
VALUES (NULL ,  '1',  'users',  'images_sizes_original',  '0');
