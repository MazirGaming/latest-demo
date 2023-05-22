DROP TABLE IF EXISTS `return_history`;

CREATE TABLE `return_history` (
`return_history_id` INT  NOT NULL ,
`return_id` INT  NOT NULL,
`return_status_id` INT  NOT NULL,
`notify` tinyINTEGER NOT NULL,
`comment` text NOT NULL,
`date_added` datetime NOT NULL,
PRIMARY KEY (`return_history_id`)
);





