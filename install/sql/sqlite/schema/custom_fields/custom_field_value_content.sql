DROP TABLE IF EXISTS `custom_field_value_content`;

CREATE TABLE `custom_field_value_content` (
`custom_field_value_id` INTEGER NOT NULL,
`language_id` INTEGER NOT NULL,
`custom_field_id` INTEGER NOT NULL,
`name` TEXT NOT NULL,
PRIMARY KEY (`custom_field_value_id`,`language_id`)
);





