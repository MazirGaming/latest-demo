DROP TABLE IF EXISTS `custom_field`;

CREATE TABLE `custom_field` (
  `custom_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_field_group_id` int NOT NULL,
  `type` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `status` tinyint NOT NULL,
  `sort_order` int NOT NULL,
  PRIMARY KEY (`custom_field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
