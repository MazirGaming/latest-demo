DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
`role_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`name` TEXT NOT NULL,
`display_name` TEXT NOT NULL,
`permissions` TEXT NOT NULL
);
