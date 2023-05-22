DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
`product_id` INTEGER PRIMARY KEY AUTOINCREMENT,
`model` TEXT NOT NULL,
`sku` TEXT NOT NULL,
`upc` TEXT NOT NULL,
`ean` TEXT NOT NULL,
`jan` TEXT NOT NULL,
`isbn` TEXT NOT NULL,
`mpn` TEXT NOT NULL,
`location` TEXT NOT NULL,
`stock_quantity` INTEGER NOT NULL DEFAULT '0',
`stock_status_id` INT  NOT NULL,
`image` TEXT NOT NULL,
`manufacturer_id` INT  NOT NULL DEFAULT 0,
`vendor_id` INT  NOT NULL DEFAULT 0,
`shipping` tinyINTEGER NOT NULL DEFAULT '1',
`price` decimal(15,4) NOT NULL DEFAULT 0.0000,
`points` INTEGER NOT NULL DEFAULT '0',
`tax_class_id` INT  NOT NULL,
`date_available` date NOT NULL DEFAULT '1000-01-01',
`weight` decimal(15,8) NOT NULL DEFAULT '0.00000000',
`weight_class_id` INT  NOT NULL DEFAULT '0',
`length` decimal(15,8) NOT NULL DEFAULT '0.00000000',
`width` decimal(15,8) NOT NULL DEFAULT '0.00000000',
`height` decimal(15,8) NOT NULL DEFAULT '0.00000000',
`length_class_id` INT  NOT NULL DEFAULT '0',
`subtract` tinyINTEGER NOT NULL DEFAULT '1',
`minimum` INT  NOT NULL DEFAULT '1',
`sort_order` INT  NOT NULL DEFAULT '0',
`status` tinyINTEGER NOT NULL DEFAULT '0',
`type` TEXT NOT NULL DEFAULT 'product',
`template` TEXT NOT NULL DEFAULT '',
`viewed` INTEGER NOT NULL DEFAULT '0',
`date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
-- PRIMARY KEY (`product_id`)
);



CREATE INDEX `product_type_status_date` ON `product` (`type`);

