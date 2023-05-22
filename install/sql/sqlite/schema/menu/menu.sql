DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
`menu_id` INT  NOT NULL ,
`name` TEXT NOT NULL DEFAULT '',
`slug` TEXT NOT NULL DEFAULT '',
PRIMARY KEY (`menu_id`)
);



CREATE INDEX `menu_menu_id` ON `menu` (`menu_id`);

