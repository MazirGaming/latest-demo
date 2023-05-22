DROP TABLE IF EXISTS `order_history`;

CREATE TABLE `order_history` (
`order_history_id` INT  NOT NULL ,
`order_id` INT  NOT NULL,
`order_status_id` INT  NOT NULL,
`notify` tinyINTEGER NOT NULL DEFAULT '0',
`comment` text NOT NULL,
`date_added` datetime NOT NULL,
PRIMARY KEY (`order_history_id`)
);





