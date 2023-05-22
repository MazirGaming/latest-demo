DROP TABLE IF EXISTS `product_category`;

CREATE TABLE `product_category` (
`product_taxonomy_item_id` INT  NOT NULL ,
`image` TEXT NOT NULL DEFAULT '',
`parent_id` INT  NOT NULL DEFAULT '0',
`top` tinyINTEGER NOT NULL DEFAULT 0,
`column` INTEGER NOT NULL DEFAULT 0,
`sort_order` INTEGER NOT NULL DEFAULT 0,
`status` tinyINTEGER NOT NULL DEFAULT 0,
PRIMARY KEY (`product_taxonomy_item_id`)
);



CREATE INDEX `product_category_parent_id` ON `product_category` (`parent_id`);

