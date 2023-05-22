DROP TABLE IF EXISTS `custom_field_group_content`;

CREATE TABLE `custom_field_group_content` (
`custom_field_group_content_id` int NOT NULL,
`language_id` int NOT NULL,
`name` TEXT NOT NULL,
PRIMARY KEY (`custom_field_group_content_id`,`language_id`)
);





