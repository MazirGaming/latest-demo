-- Currencies

	-- get all return action

	PROCEDURE getAll(
		IN tax_class_id INT,
		IN start INT,
		IN limit INT,
		OUT fetch_all, 
		OUT fetch_one,
	)
	BEGIN
		-- tax_rule
		SELECT *
			FROM "tax_rule" AS "tax_rule" WHERE 1 = 1

			
		@IF !empty(:tax_class_id) 
		THEN			
			AND "tax_class_id" = :tax_class_id
		END @IF		
		
		@IF !empty(:limit) 
		THEN			
			LIMIT :limit OFFSET :start
		END @IF
		
		ORDER BY priority;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(tax_rule.tax_rule_id, tax_rule) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;		
			
	END	
	
	-- get tax_rule

	PROCEDURE get(
		IN tax_rule_id INT,
		OUT fetch_row, 
	)
	BEGIN
		-- tax_rule
		SELECT *
			FROM "tax_rule" as _ WHERE tax_rule_id = :tax_rule_id;
	END
	
	-- add tax_rule

	PROCEDURE add(
		IN tax_rule ARRAY,
		IN tax_class_id INT,
		OUT insert_id
	)
	BEGIN
		-- BEGIN transaction;

		DELETE FROM "tax_rule" WHERE tax_class_id = :tax_class_id;
		
		-- allow only table fields and set defaults for missing values
		:tax_rule_data  = @FILTER(:tax_rule, tax_rule);
		
		
		@EACH(:tax_rule_data) 
		INSERT INTO "tax_rule" 
			
			( @KEYS(:each), tax_class_id )
			
	  	VALUES ( :each, :tax_class_id );
		
		-- END transaction;

	END
	
	-- edit tax_rule
	CREATE PROCEDURE edit(
		IN tax_rule ARRAY,
		IN tax_rule_id INT,
		OUT affected_rows
	)
	BEGIN

		-- allow only table fields and set defaults for missing values
		@FILTER(:tax_rule, tax_rule);

		UPDATE "tax_rule"
			
			SET @LIST(:tax_rule) 
			
		WHERE tax_rule_id = :tax_rule_id


	END
	
