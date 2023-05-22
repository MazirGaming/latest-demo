DROP TABLE IF EXISTS `weight_class_content`;

CREATE TABLE `weight_class_content` (
`weight_class_id` INT  NOT NULL,
`language_id` INT  NOT NULL,
`name` TEXT NOT NULL,
`unit` TEXT NOT NULL,
PRIMARY KEY (`weight_class_id`,`language_id`)
);





