DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `country_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `iso_code_2` varchar(2) NOT NULL,
  `iso_code_3` varchar(3) NOT NULL,
  `address_format` varchar(191) NOT NULL DEFAULT '',
  `postcode_required` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;