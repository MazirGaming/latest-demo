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
		-- voucher
		SELECT *
			FROM "voucher" AS "voucher" WHERE 1 = 1
			
		
		LIMIT :limit OFFSET :start;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(voucher.voucher_id, voucher) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;		
			
	END	
	
	-- get voucher

	PROCEDURE get(
		IN voucher_id INT,
		OUT fetch_row, 
	)
	BEGIN
		-- voucher
		SELECT *
			FROM "voucher" as _ WHERE voucher_id = :voucher_id;
	END
	
	-- add voucher

	PROCEDURE add(
		IN voucher ARRAY,
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		:voucher_data  = @FILTER(:voucher, voucher);
		
		
		INSERT INTO "voucher" 
			
			( @KEYS(:voucher) )
			
	  	VALUES ( :voucher );

	END
	
	-- edit voucher
	CREATE PROCEDURE edit(
		IN voucher ARRAY,
		IN voucher_id INT,
		OUT affected_rows
	)
	BEGIN

		-- allow only table fields and set defaults for missing values
		@FILTER(:voucher, voucher);

		UPDATE "voucher"
			
			SET @LIST(:voucher) 
			
		WHERE voucher_id = :voucher_id


	END
	
