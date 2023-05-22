DROP TABLE IF EXISTS `menu_to_site`;

CREATE TABLE `menu_to_site` (
`menu_id` INT  NOT NULL,
`site_id` tinyINTEGER NOT NULL,
PRIMARY KEY (`menu_id`,`site_id`)
);





