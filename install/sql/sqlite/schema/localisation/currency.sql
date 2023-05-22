DROP TABLE IF EXISTS `currency`;

CREATE TABLE `currency` (
`currency_id` INT  NOT NULL ,
`name` TEXT NOT NULL,
`code` TEXT NOT NULL,
`symbol_left` TEXT NOT NULL,
`symbol_right` TEXT NOT NULL,
`decimal_place` char(1) NOT NULL,
`value` double(15,8) NOT NULL,
`status` tinyINTEGER NOT NULL,
`date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`currency_id`)
);





