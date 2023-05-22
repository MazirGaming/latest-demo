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
		-- coupon
		SELECT *
			FROM `coupon` AS `coupon` WHERE 1 = 1
			
		
		LIMIT :start, :limit;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(coupon.coupon_id, coupon) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;		
			
	END	
	
	-- get coupon

	PROCEDURE get(
		IN coupon_id INT,
		OUT fetch_row, 
	)
	BEGIN
		-- coupon
		SELECT *
			FROM `coupon` as _ WHERE coupon_id = :coupon_id;
	END
	
	-- add coupon

	PROCEDURE add(
		IN coupon ARRAY,
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		:coupon_data  = @FILTER(:coupon, coupon);
		
		
		INSERT INTO `coupon` 
			
			( @KEYS(:coupon_data) )
			
	  	VALUES ( :coupon_data );

	END
	
	-- edit coupon
	CREATE PROCEDURE edit(
		IN coupon ARRAY,
		IN coupon_id INT,
		OUT affected_rows
	)
	BEGIN

		-- allow only table fields and set defaults for missing values
		@FILTER(:coupon, coupon);

		UPDATE `coupon`
			
			SET @LIST(:coupon) 
			
		WHERE coupon_id = :coupon_id


	END
	
