DROP TABLE IF EXISTS `zone_to_zone_group`;

CREATE TABLE `zone_to_zone_group` (
`zone_to_zone_group_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`country_id` INT  NOT NULL,
`zone_id` INT  NOT NULL DEFAULT '0',
`zone_group_id` INT  NOT NULL,
`date_added` datetime NOT NULL
-- PRIMARY KEY (`zone_to_zone_group_id`)
);





