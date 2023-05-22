DROP TABLE IF EXISTS `shipping_courier`;

CREATE TABLE `shipping_courier` (
`shipping_courier_id` INT  NOT NULL,
`shipping_courier_code` TEXT NOT NULL,
`shipping_courier_name` TEXT NOT NULL,
PRIMARY KEY (`shipping_courier_id`)
);
