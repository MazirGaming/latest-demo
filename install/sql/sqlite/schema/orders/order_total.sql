DROP TABLE IF EXISTS `order_total`;

CREATE TABLE `order_total` (
`order_total_id` INTEGER NOT NULL ,
`order_id` INT  NOT NULL,
`code` TEXT NOT NULL,
`title` TEXT NOT NULL,
`value` decimal(15,4) NOT NULL DEFAULT '0.0000',
`sort_order` INTEGER NOT NULL,
PRIMARY KEY (`order_total_id`)
);



CREATE INDEX `order_total_order_id` ON `order_total` (`order_id`);

