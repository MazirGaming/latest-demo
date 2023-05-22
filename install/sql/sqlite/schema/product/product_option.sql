DROP TABLE IF EXISTS `product_option`;

CREATE TABLE `product_option` (
`product_option_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`product_id` INT  NOT NULL,
`option_id` INT  NOT NULL,
`value` text NOT NULL,
`required` tinyINTEGER NOT NULL
-- PRIMARY KEY (`product_option_id`)
);





