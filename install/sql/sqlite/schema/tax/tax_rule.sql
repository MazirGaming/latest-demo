DROP TABLE IF EXISTS `tax_rule`;

CREATE TABLE `tax_rule` (
`tax_rule_id`  INTEGER PRIMARY KEY AUTOINCREMENT,
`tax_class_id` INT  NOT NULL,
`tax_rate_id` INT  NOT NULL,
`based` TEXT NOT NULL,
`priority` INTEGER NOT NULL DEFAULT '1'
-- PRIMARY KEY (`tax_rule_id`)
);

CREATE INDEX `tax_rule_index` ON `tax_rule` (`tax_class_id`, `tax_rate_id`);



