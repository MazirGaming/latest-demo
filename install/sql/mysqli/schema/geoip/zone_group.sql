DROP TABLE IF EXISTS `zone_group`;

CREATE TABLE `zone_group` (
  `zone_group_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(191) NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`zone_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;