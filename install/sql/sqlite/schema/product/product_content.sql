DROP TABLE IF EXISTS `product_content`;

CREATE TABLE `product_content` (
`product_id` INT  NOT NULL,
`language_id` INT  NOT NULL,
`name` TEXT NOT NULL DEFAULT "",
`slug` TEXT NOT NULL DEFAULT "",
`content` text,
`tag` text,
`meta_title` TEXT NOT NULL DEFAULT "",
`meta_description` TEXT NOT NULL DEFAULT "",
`meta_keyword` TEXT NOT NULL DEFAULT "",
PRIMARY KEY (`product_id`,`language_id`)
-- FULLTEXT `search` (`name`,`content`)
);



CREATE INDEX `product_content_slug` ON `product_content` (`slug`);

