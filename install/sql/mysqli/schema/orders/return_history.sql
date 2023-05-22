DROP TABLE IF EXISTS `return_history`;

CREATE TABLE `return_history` (
  `return_history_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `return_id` INT UNSIGNED NOT NULL,
  `return_status_id` INT UNSIGNED NOT NULL,
  `notify` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`return_history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;