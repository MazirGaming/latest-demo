DROP TABLE IF EXISTS `coupon_category`;

CREATE TABLE `coupon_category` (
`coupon_id` INT  NOT NULL,
`taxonomy_item_id` INT  NOT NULL,
PRIMARY KEY (`coupon_id`,`taxonomy_item_id`)
);





