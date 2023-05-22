DROP TABLE IF EXISTS `zone_to_zone_group`;

CREATE TABLE `zone_to_zone_group` (
  `zone_to_zone_group_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `country_id` INT UNSIGNED NOT NULL,
  `zone_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `zone_group_id` INT UNSIGNED NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`zone_to_zone_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;