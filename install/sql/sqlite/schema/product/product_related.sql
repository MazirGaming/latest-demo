DROP TABLE IF EXISTS `product_related`;

CREATE TABLE `product_related` (
`product_id` INT  NOT NULL,
`related_id` INT  NOT NULL,
PRIMARY KEY (`product_id`,`related_id`)
);





