DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_group_id` INT UNSIGNED NOT NULL DEFAULT 1,
  `username` varchar(60) NOT NULL DEFAULT '',
  `first_name` varchar(32) NOT NULL DEFAULT '',
  `last_name` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(191) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `phone_number` varchar(32) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '2022-05-01 00:00:00',
  `status` INT UNSIGNED NOT NULL DEFAULT '0',
  `display_name` varchar(250) NOT NULL DEFAULT '',
  `token` varchar(32) NOT NULL DEFAULT '',
--	`role_id` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
