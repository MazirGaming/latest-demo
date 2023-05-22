DROP TABLE IF EXISTS `custom_field_value`;

CREATE TABLE `custom_field_value` (
`custom_field_value_id` INTEGER NOT NULL ,
`custom_field_id` INTEGER NOT NULL,
`sort_order` INTEGER NOT NULL,
PRIMARY KEY (`custom_field_value_id`)
);





