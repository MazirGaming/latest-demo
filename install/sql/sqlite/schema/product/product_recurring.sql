DROP TABLE IF EXISTS `product_recurring`;

CREATE TABLE `product_recurring` (
`product_id` INT  NOT NULL,
`recurring_id` INT  NOT NULL,
`user_group_id` INT  NOT NULL,
PRIMARY KEY (`product_id`,`recurring_id`,`user_group_id`)
);





