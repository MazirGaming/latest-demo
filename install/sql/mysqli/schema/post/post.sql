DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `post_id` INT unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` INT unsigned NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL DEFAULT 'publish',
  `image` varchar(191) NOT NULL DEFAULT '',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `password` varchar(191) NOT NULL DEFAULT '',
  `parent` INT unsigned NOT NULL DEFAULT '0',
  `sort_order` INT UNSIGNED NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT 'post',
  `template` varchar(191) NOT NULL DEFAULT '',
  `comment_count` INT NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '2022-05-01 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '2022-05-01 00:00:00',
  PRIMARY KEY (`post_id`),
  KEY `type_status_date` (`type`,`status`,`sort_order`,`date_added`,`post_id`),
  KEY `parent` (`parent`),
  KEY `author` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
