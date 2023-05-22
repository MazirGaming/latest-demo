DROP TABLE IF EXISTS `custom_field_group`;

CREATE TABLE `custom_field_group` (
`custom_field_group_id` int NOT NULL ,
`name` text NOT NULL,
`status` tinyint NOT NULL,
`sort_order` int NOT NULL,
PRIMARY KEY (`custom_field_group_id`)
);





