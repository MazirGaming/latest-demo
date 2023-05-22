DROP TABLE IF EXISTS `coupon_history`;

CREATE TABLE `coupon_history` (
  `coupon_history_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `coupon_id` INT UNSIGNED NOT NULL,
  `order_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`coupon_history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
