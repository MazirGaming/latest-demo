DROP TABLE IF EXISTS `custom_field`;

CREATE TABLE `custom_field` (
`custom_field_id` INTEGER NOT NULL ,
`custom_field_group_id` int NOT NULL,
`type` TEXT NOT NULL,
`value` text NOT NULL,
`status` tinyint NOT NULL,
`sort_order` int NOT NULL,
PRIMARY KEY (`custom_field_id`)
);





