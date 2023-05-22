DROP TABLE IF EXISTS `download_content`;

CREATE TABLE `download_content` (
`download_id` INT  NOT NULL,
`language_id` INT  NOT NULL,
`name` TEXT NOT NULL,
PRIMARY KEY (`download_id`,`language_id`)
);





