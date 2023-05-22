DROP TABLE IF EXISTS `user_reward`;

CREATE TABLE `user_reward` (
  `user_reward_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `order_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `points` int(8) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_reward_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;