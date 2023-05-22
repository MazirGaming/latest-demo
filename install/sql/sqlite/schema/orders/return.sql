DROP TABLE IF EXISTS `return`;

CREATE TABLE `return` (
`return_id` INT  NOT NULL ,
`order_id` INT  NOT NULL,
`product_id` INT  NOT NULL,
`user_id` INT  NOT NULL,
`first_name` TEXT NOT NULL,
`last_name` TEXT NOT NULL,
`email` TEXT NOT NULL,
`phone_number` TEXT NOT NULL,
`product` TEXT NOT NULL,
`model` TEXT NOT NULL,
`quantity` INTEGER NOT NULL,
`opened` tinyINTEGER NOT NULL,
`return_reason_id` INT  NOT NULL,
`return_action_id` INT  NOT NULL,
`return_status_id` INT  NOT NULL,
`comment` text NOT NULL,
`date_ordered` date NOT NULL,
`date_added` datetime NOT NULL,
`date_modified` datetime NOT NULL,
PRIMARY KEY (`return_id`)
);





