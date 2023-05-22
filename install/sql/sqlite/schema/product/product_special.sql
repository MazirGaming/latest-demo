DROP TABLE IF EXISTS `product_special`;

CREATE TABLE `product_special` (
`product_special_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`product_id` INT  NOT NULL,
`user_group_id` INT  NOT NULL,
`priority` INTEGER NOT NULL DEFAULT '1',
`price` decimal(15,4) NOT NULL DEFAULT '0.0000',
`date_start` date NOT NULL DEFAULT '1000-01-01',
`date_end` date NOT NULL DEFAULT '1000-01-01'
-- PRIMARY KEY (`product_special_id`)
);

CREATE INDEX `product_special_product_id` ON `product_special` (`product_id`);

