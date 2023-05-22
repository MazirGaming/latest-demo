DROP TABLE IF EXISTS `zone`;

CREATE TABLE `zone` (
`zone_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`country_id` INT  NOT NULL,
`name` TEXT NOT NULL,
`code` TEXT NOT NULL,
`status` tinyINTEGER NOT NULL DEFAULT '1'
-- PRIMARY KEY (`zone_id`)
);





