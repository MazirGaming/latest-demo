DROP TABLE IF EXISTS `user_group`;

CREATE TABLE `user_group` (
`user_group_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`approval` INTEGER NOT NULL,
`sort_order` INTEGER NOT NULL
-- PRIMARY KEY (`user_group_id`)
);
