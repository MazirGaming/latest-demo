DROP TABLE IF EXISTS `download_report`;

CREATE TABLE `download_report` (
`download_report_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`download_id` INT  NOT NULL,
`site_id` tinyINTEGER NOT NULL,
`ip` TEXT NOT NULL,
`country` TEXT NOT NULL,
`date_added` datetime NOT NULL
-- PRIMARY KEY (`download_report_id`)
);





