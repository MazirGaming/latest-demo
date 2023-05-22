DROP TABLE IF EXISTS `zone_group`;

CREATE TABLE `zone_group` (
`zone_group_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`name` TEXT NOT NULL,
`description` TEXT NOT NULL,
`date_added` datetime NOT NULL
-- PRIMARY KEY (`zone_group_id`)
);





