DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
`site_id` INTEGER,
`key` TEXT NOT NULL,
`value` text NOT NULL,
 PRIMARY KEY (`site_id`, `key`)
);
