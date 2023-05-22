DROP TABLE IF EXISTS `product_attribute`;

CREATE TABLE `product_attribute` (
  `product_id` INT UNSIGNED NOT NULL,
  `attribute_id` INT UNSIGNED NOT NULL,
  `language_id` INT UNSIGNED NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`product_id`,`attribute_id`,`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;