-- Currencies

	-- get all return action

	PROCEDURE getAll(
		IN language_id INT,
		IN start INT,
		IN limit INT,
		OUT fetch_all, 
		OUT fetch_one,
	)
	BEGIN
		-- tax_class
		SELECT *
			FROM `tax_class` AS `tax_class` WHERE 1 = 1
			
		
		LIMIT :start, :limit;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(tax_class.tax_class_id, tax_class) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;		
			
	END	
	
	-- get tax_class

	PROCEDURE get(
		IN tax_class_id INT,
		OUT fetch_row, 
	)
	BEGIN
		-- tax_class
		SELECT *
			FROM `tax_class` as _ WHERE tax_class_id = :tax_class_id;
	END	
	
	-- get tax class and tax rules for zone

	PROCEDURE getZoneRules(
		IN country_id INT,
		IN zone_id INT,
		IN based CHAR,
		OUT fetch_all, 
	)
	BEGIN
	
		SELECT tax_rule.`tax_class_id`, tax_rate.`tax_rate_id`, tax_rate.`name`, tax_rate.`rate`, tax_rate.`type`, tax_rule.`priority` 
			FROM `tax_rule`
				LEFT JOIN `tax_rate` ON (tax_rule.`tax_rate_id` = tax_rate.`tax_rate_id`) 
				LEFT JOIN `zone_to_zone_group` ON (tax_rate.`zone_group_id` = zone_to_zone_group.`zone_group_id`) 
				LEFT JOIN `zone_group` ON (tax_rate.`zone_group_id` = zone_group.`zone_group_id`) 
		WHERE tax_rule.`based` = :based  AND zone_to_zone_group.`country_id` = :country_id AND (zone_to_zone_group.`zone_id` = '0' OR zone_to_zone_group.`zone_id` = :zone_id) 
			ORDER BY tax_rule.`priority` ASC
	
	END	
	
	-- delete tax_class

	PROCEDURE delete(
		IN tax_class_id ARRAY,
		OUT affected_rows, 
		OUT affected_rows, 
	)
	BEGIN
		-- tax_rules
		DELETE FROM `tax_rule` WHERE tax_class_id IN (:tax_class_id);

		-- tax_class
		DELETE FROM `tax_class` WHERE tax_class_id IN (:tax_class_id);
	END
	
	-- add tax_class

	PROCEDURE add(
		IN tax_class ARRAY,
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		:tax_class_data  = @FILTER(:tax_class, tax_class);
		
		
		INSERT INTO `tax_class` 
			
			( @KEYS(:tax_class_data) )
			
	  	VALUES ( :tax_class_data );

	END
	
	-- edit tax_class
	CREATE PROCEDURE edit(
		IN tax_class ARRAY,
		IN tax_class_id INT,
		OUT affected_rows
	)
	BEGIN

		-- allow only table fields and set defaults for missing values
		@FILTER(:tax_class, tax_class);

		UPDATE `tax_class`
			
			SET @LIST(:tax_class) 
			
		WHERE tax_class_id = :tax_class_id


	END
	
