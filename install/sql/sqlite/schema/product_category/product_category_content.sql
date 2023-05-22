DROP TABLE IF EXISTS `product_category_content`;

CREATE TABLE `product_category_content` (
`product_taxonomy_item_id` INT  NOT NULL,
`language_id` INT  NOT NULL,
`name` TEXT NOT NULL,
`slug` TEXT NOT NULL DEFAULT '',
`description` text NOT NULL,
`meta_title` TEXT NOT NULL DEFAULT '',
`meta_description` TEXT NOT NULL DEFAULT '',
`meta_keyword` TEXT NOT NULL DEFAULT '',
PRIMARY KEY (`product_taxonomy_item_id`,`language_id`)
);



CREATE INDEX `product_category_content_name` ON `product_category_content` (`name`);

