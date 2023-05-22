DROP TABLE IF EXISTS `location`;

CREATE TABLE `location` (
`location_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`name` TEXT NOT NULL,
`address` text NOT NULL,
`phone_number` TEXT NOT NULL,
`fax` TEXT NOT NULL,
`geocode` TEXT NOT NULL,
`image` TEXT NOT NULL,
`open` text NOT NULL,
`comment` text NOT NULL
-- PRIMARY KEY (`location_id`)
);



CREATE INDEX `location_name` ON `location` (`name`);

