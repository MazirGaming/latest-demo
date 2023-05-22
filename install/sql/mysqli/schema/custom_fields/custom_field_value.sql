DROP TABLE IF EXISTS `custom_field_value`;

CREATE TABLE `custom_field_value` (
  `custom_field_value_id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_field_id` int(11) NOT NULL,
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`custom_field_value_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
