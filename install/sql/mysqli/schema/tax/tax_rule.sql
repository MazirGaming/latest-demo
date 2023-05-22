DROP TABLE IF EXISTS `tax_rule`;

CREATE TABLE `tax_rule` (
  `tax_rule_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tax_class_id` INT UNSIGNED NOT NULL,
  `tax_rate_id` INT UNSIGNED NOT NULL,
  `based` varchar(10) NOT NULL,
  `priority` int(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`tax_rule_id`),
  KEY `tax_rule` (`tax_class_id`, `tax_rate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;