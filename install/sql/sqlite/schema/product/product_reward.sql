DROP TABLE IF EXISTS `product_reward`;

CREATE TABLE `product_reward` (
`product_reward_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`product_id` INT  NOT NULL DEFAULT '0',
`user_group_id` INT  NOT NULL DEFAULT '0',
`points` INTEGER NOT NULL DEFAULT '0'
-- PRIMARY KEY (`product_reward_id`)
);





