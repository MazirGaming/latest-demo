DROP TABLE IF EXISTS `product_option_value`;

CREATE TABLE `product_option_value` (
`product_option_value_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`product_option_id` INT  NOT NULL,
`product_id` INT  NOT NULL,
`option_id` INT  NOT NULL,
`option_value_id` INT  NOT NULL,
`quantity` INTEGER NOT NULL,
`subtract` tinyINTEGER NOT NULL,
`price` decimal(15,4) NOT NULL,
`price_prefix` TEXT NOT NULL,
`points` INTEGER NOT NULL,
`points_prefix` TEXT NOT NULL,
`weight` decimal(15,8) NOT NULL,
`weight_prefix` TEXT NOT NULL
-- PRIMARY KEY (`product_option_value_id`)
);





