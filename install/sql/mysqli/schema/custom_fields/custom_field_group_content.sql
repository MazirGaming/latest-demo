DROP TABLE IF EXISTS `custom_field_group_content`;

CREATE TABLE `custom_field_group_content` (
  `custom_field_group_content_id` int NOT NULL,
  `language_id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`custom_field_group_content_id`,`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
