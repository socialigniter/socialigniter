CREATE TABLE `occurrences` (
  `occurrence_id` int(6) NOT NULL AUTO_INCREMENT,
  `type` char(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `location_id` int(6) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `age_start` int(4) DEFAULT NULL,
  `age_end` int(4) DEFAULT NULL,
  `price` decimal(7,2) DEFAULT NULL,
  `details` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details_more` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `student_limit` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_size` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`occurrence_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
