DROP TABLE IF EXISTS `length_class_content`;

CREATE TABLE `length_class_content` (
`length_class_id` INT  NOT NULL,
`language_id` INT  NOT NULL,
`name` TEXT NOT NULL,
`unit` TEXT NOT NULL,
PRIMARY KEY (`length_class_id`,`language_id`)
);





