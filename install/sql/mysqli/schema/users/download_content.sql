DROP TABLE IF EXISTS `download_content`;

CREATE TABLE `download_content` (
  `download_id` INT UNSIGNED NOT NULL,
  `language_id` INT UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`download_id`,`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;