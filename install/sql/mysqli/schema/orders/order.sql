DROP TABLE IF EXISTS `order`;

CREATE TABLE `order` (
  `order_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_no` INT UNSIGNED NOT NULL DEFAULT '0',
  `invoice_prefix` varchar(26) NOT NULL DEFAULT 'INV-',
  `site_id` tinyint(6) NOT NULL DEFAULT '0',
  `store_name` varchar(64) NOT NULL,
  `store_url` varchar(191) NOT NULL,
  `user_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `user_group_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `email` varchar(96) NOT NULL,
  `phone_number` varchar(32) NOT NULL DEFAULT '',
  `custom_field` text,
  `billing_first_name` varchar(32) NOT NULL,
  `billing_last_name` varchar(32) NOT NULL,
  `billing_company` varchar(60) NOT NULL,
  `billing_address_1` varchar(191) NOT NULL,
  `billing_address_2` varchar(191) NOT NULL,
  `billing_city` varchar(128) NOT NULL,
  `billing_postcode` varchar(10) NOT NULL,
-- `billing_country` varchar(128) NOT NULL,
  `billing_country_id` INT UNSIGNED NOT NULL,
--  `billing_zone` varchar(128) NOT NULL,
  `billing_zone_id` INT UNSIGNED NOT NULL,
--  `billing_address_format` text NOT NULL,
  `billing_custom_field` text,
--  `billing_method` varchar(128) NOT NULL,
  `payment_method` varchar(128) NOT NULL,
  `shipping_first_name` varchar(32) NOT NULL DEFAULT '',
  `shipping_last_name` varchar(32) NOT NULL DEFAULT '',
  `shipping_company` varchar(60) NOT NULL DEFAULT '',
  `shipping_address_1` varchar(191) NOT NULL DEFAULT '',
  `shipping_address_2` varchar(191) NOT NULL DEFAULT '',
  `shipping_city` varchar(128) NOT NULL DEFAULT '',
  `shipping_postcode` varchar(10) NOT NULL DEFAULT '',
-- `shipping_country` varchar(128) NOT NULL,
  `shipping_country_id` INT UNSIGNED NOT NULL DEFAULT 0,
--   `shipping_zone` varchar(128) NOT NULL,
  `shipping_zone_id` INT UNSIGNED NOT NULL DEFAULT 0,
--  `shipping_address_format` text NOT NULL,
  `shipping_custom_field` text,
--  `shipping_method` varchar(128) NOT NULL,
  `shipping_method` varchar(128) NOT NULL,
  `comment` text,
  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `order_status_id` INT UNSIGNED NOT NULL DEFAULT '0',
--  `affiliate_id` INT UNSIGNED NOT NULL,
--  `commission` decimal(15,4) NOT NULL,
--  `marketing_id` INT UNSIGNED NOT NULL,
--  `tracking` varchar(64) NOT NULL,
  `language_id` INT UNSIGNED NOT NULL,
  `currency_id` INT UNSIGNED NOT NULL,
--  `currency_code` varchar(3) NOT NULL,
--  `currency_value` decimal(15,8) NOT NULL DEFAULT '1.00000000', ---
  `ip` varchar(40) NOT NULL DEFAULT '',
  `forwarded_ip` varchar(40) NOT NULL DEFAULT '',
  `user_agent` varchar(191) NOT NULL DEFAULT '',
  `accept_language` varchar(191) NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT current_timestamp,
  `date_modified` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;