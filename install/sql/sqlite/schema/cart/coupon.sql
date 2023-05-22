DROP TABLE IF EXISTS `coupon`;

CREATE TABLE `coupon` (
`coupon_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`name` TEXT NOT NULL,
`code` TEXT NOT NULL,
`type` char(1) NOT NULL,
`discount` decimal(15,4) NOT NULL,
`logged` tinyINTEGER NOT NULL,
`shipping` tinyINTEGER NOT NULL,
`total` decimal(15,4) NOT NULL,
`date_start` date NOT NULL DEFAULT '1000-01-01',
`date_end` date NOT NULL DEFAULT '1000-01-01',
`uses_total` INT  NOT NULL,
`uses_user` TEXT NOT NULL,
`status` tinyINTEGER NOT NULL,
`date_added` datetime NOT NULL
-- PRIMARY KEY (`coupon_id`)
);





