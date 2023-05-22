DROP TABLE IF EXISTS `user_wishlist`;

CREATE TABLE `user_wishlist` (
`user_id` INT  NOT NULL,
`product_id` INT  NOT NULL,
`date_added` datetime NOT NULL,
PRIMARY KEY (`user_id`,`product_id`)
);





