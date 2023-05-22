DROP TABLE IF EXISTS `product_attribute`;

CREATE TABLE `product_attribute` (
`product_id` INT  NOT NULL,
`attribute_id` INT  NOT NULL,
`language_id` INT  NOT NULL,
`text` text NOT NULL,
PRIMARY KEY (`product_id`,`attribute_id`,`language_id`)
);





