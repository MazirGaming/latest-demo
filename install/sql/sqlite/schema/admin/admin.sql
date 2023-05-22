pragma journal_mode = WAL;
pragma synchronous = normal;
pragma temp_store = memory;
pragma mmap_size = 30000000000;
  
DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
`admin_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`username` TEXT NOT NULL DEFAULT '',
`password` TEXT NOT NULL DEFAULT '',
`email` TEXT NOT NULL DEFAULT '',
`url` TEXT NOT NULL DEFAULT '',
`registered` datetime NOT NULL DEFAULT '2022-05-01 00:00:00',
`status` INT  NOT NULL DEFAULT '0',
`display_name` TEXT NOT NULL DEFAULT '',
`role_id` INT  DEFAULT NULL,
`token` TEXT NOT NULL DEFAULT ''
--PRIMARY KEY (`admin_id`)
);

CREATE INDEX `admin_user` ON `admin` (`user`);
CREATE INDEX `admin_email` ON `admin` (`email`);

