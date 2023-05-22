DROP TABLE IF EXISTS `product_review`;

CREATE TABLE `product_review` (
  `product_review_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `author` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `rating` SMALLINT(1) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_review_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
