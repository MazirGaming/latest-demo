DROP TABLE IF EXISTS `product_recurring`;

CREATE TABLE `product_recurring` (
  `product_id` INT UNSIGNED NOT NULL,
  `recurring_id` INT UNSIGNED NOT NULL,
  `user_group_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`product_id`,`recurring_id`,`user_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;