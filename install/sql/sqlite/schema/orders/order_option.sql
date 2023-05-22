DROP TABLE IF EXISTS `order_option`;

CREATE TABLE `order_option` (
`order_option_id` INT  NOT NULL ,
`order_id` INT  NOT NULL,
`order_product_id` INT  NOT NULL,
`product_option_id` INT  NOT NULL,
`product_option_value_id` INT  NOT NULL DEFAULT '0',
`name` TEXT NOT NULL,
`value` text NOT NULL,
`type` TEXT NOT NULL,
PRIMARY KEY (`order_option_id`)
);





