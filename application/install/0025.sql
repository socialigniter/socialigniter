CREATE INDEX last_activity_idx ON users_sessions(last_activity); ALTER TABLE users_sessions MODIFY user_agent VARCHAR(120);
ALTER TABLE `uploads` ADD `user_id` INT(11) NULL AFTER  `upload_id`;
