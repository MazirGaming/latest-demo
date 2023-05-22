DROP TABLE IF EXISTS `tax_rate`;

CREATE TABLE `tax_rate` (
  `tax_rate_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `zone_group_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL,
  `rate` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `type` char(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tax_rate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;