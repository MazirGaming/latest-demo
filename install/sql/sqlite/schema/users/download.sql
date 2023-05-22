DROP TABLE IF EXISTS `download`;

CREATE TABLE `download` (
`download_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`filename` TEXT NOT NULL,
`mask` TEXT NOT NULL,
`date_added` datetime NOT NULL
--PRIMARY KEY (`download_id`)
);





