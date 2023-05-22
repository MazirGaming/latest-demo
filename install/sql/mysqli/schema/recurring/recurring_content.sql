DROP TABLE IF EXISTS `recurring_content`;

CREATE TABLE `recurring_content` (
  `recurring_id` INT UNSIGNED NOT NULL,
  `language_id` INT UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`recurring_id`,`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;