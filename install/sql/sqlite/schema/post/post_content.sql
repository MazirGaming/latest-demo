DROP TABLE IF EXISTS `post_content`;

CREATE TABLE `post_content` (
`post_id` INT NOT NULL,
`language_id` INT  NOT NULL,
`name` TEXT NOT NULL DEFAULT "",
`slug` TEXT NOT NULL DEFAULT "",
`content` TEXT,
`excerpt` text,
`meta_keywords` text,
`meta_description` text
,PRIMARY KEY (`post_id`,`language_id`)
-- FULLTEXT `search` (`name`,`content`)
);

CREATE INDEX `post_content_slug` ON `post_content` (`slug`);

