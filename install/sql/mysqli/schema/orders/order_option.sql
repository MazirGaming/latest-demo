DROP TABLE IF EXISTS `order_option`;

CREATE TABLE `order_option` (
  `order_option_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT UNSIGNED NOT NULL,
  `order_product_id` INT UNSIGNED NOT NULL,
  `product_option_id` INT UNSIGNED NOT NULL,
  `product_option_value_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(191) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(32) NOT NULL,
  PRIMARY KEY (`order_option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;