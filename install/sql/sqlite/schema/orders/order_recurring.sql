DROP TABLE IF EXISTS `order_recurring`;

CREATE TABLE `order_recurring` (
`order_recurring_id` INT  NOT NULL ,
`order_id` INT  NOT NULL,
`reference` TEXT NOT NULL,
`product_id` INT  NOT NULL,
`product_name` TEXT NOT NULL,
`product_quantity` INT  NOT NULL,
`recurring_id` INT  NOT NULL,
`recurring_name` TEXT NOT NULL,
`recurring_content` TEXT NOT NULL,
`recurring_frequency` TEXT NOT NULL,
`recurring_cycle` smallINTEGER NOT NULL,
`recurring_duration` smallINTEGER NOT NULL,
`recurring_price` decimal(10,4) NOT NULL,
`trial` tinyINTEGER NOT NULL,
`trial_frequency` TEXT NOT NULL,
`trial_cycle` smallINTEGER NOT NULL,
`trial_duration` smallINTEGER NOT NULL,
`trial_price` decimal(10,4) NOT NULL,
`status` tinyINTEGER NOT NULL,
`date_added` datetime NOT NULL,
PRIMARY KEY (`order_recurring_id`)
);





