DROP TABLE IF EXISTS `custom_field_value_content`;

CREATE TABLE `custom_field_value_content` (
  `custom_field_value_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`custom_field_value_id`,`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
