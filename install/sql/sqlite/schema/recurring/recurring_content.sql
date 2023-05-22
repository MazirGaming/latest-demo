DROP TABLE IF EXISTS `recurring_content`;

CREATE TABLE `recurring_content` (
`recurring_id` INT  NOT NULL,
`language_id` INT  NOT NULL,
`name` TEXT NOT NULL,
PRIMARY KEY (`recurring_id`,`language_id`)
);





