DROP TABLE IF EXISTS `return_action`;

CREATE TABLE `return_action` (
  `return_action_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `language_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`return_action_id`,`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;