DROP TABLE IF EXISTS `address`;

CREATE TABLE `address` (
  `address_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `company` varchar(60) NOT NULL,
  `address_1` varchar(128) NOT NULL,
  `address_2` varchar(128) NOT NULL,
  `city` varchar(128) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `country_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `zone_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `custom_field` text,
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;