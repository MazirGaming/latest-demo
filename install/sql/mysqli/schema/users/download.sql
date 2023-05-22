DROP TABLE IF EXISTS `download`;


CREATE TABLE `download` (
  `download_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` varchar(160) NOT NULL,
  `mask` varchar(128) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`download_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;