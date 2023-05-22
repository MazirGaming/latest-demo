DROP TABLE IF EXISTS `voucher_history`;

CREATE TABLE `voucher_history` (
  `voucher_history_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `voucher_id` INT UNSIGNED NOT NULL,
  `order_id` INT UNSIGNED NOT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`voucher_history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;