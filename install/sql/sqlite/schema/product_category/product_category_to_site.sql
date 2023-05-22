DROP TABLE IF EXISTS `product_category_to_site`;

CREATE TABLE `product_category_to_site` (
`product_taxonomy_item_id` INT  NOT NULL,
`site_id` tinyINTEGER NOT NULL,
PRIMARY KEY (`product_taxonomy_item_id`,`site_id`)
);





