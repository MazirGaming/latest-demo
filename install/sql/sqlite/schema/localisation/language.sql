DROP TABLE IF EXISTS `language`;

CREATE TABLE `language` (
`language_id` INT  NOT NULL ,
`name` TEXT NOT NULL,
`code` TEXT NOT NULL,
`locale` TEXT NOT NULL,
`sort_order` INTEGER NOT NULL DEFAULT 0,
`status` tinyINTEGER NOT NULL,
`default` tinyINTEGER NOT NULL DEFAULT 0,
PRIMARY KEY (`language_id`)
);



CREATE INDEX `language_name` ON `language` (`name`);

