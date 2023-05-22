DROP TABLE IF EXISTS `length_class_content`;

CREATE TABLE `length_class_content` (
  `length_class_id` INT UNSIGNED NOT NULL,
  `language_id` INT UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `unit` varchar(4) NOT NULL,
  PRIMARY KEY (`length_class_id`,`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;