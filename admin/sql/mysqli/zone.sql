-- Zone

	-- get all zones

	PROCEDURE getAll(
		IN country_id INT,
		IN status INT,
		IN start INT,
		IN limit INT,
		OUT fetch_all, 
		OUT fetch_one,
	)
	BEGIN
		-- zone
		SELECT country.name as country,zone.*
			FROM zone AS zone
		LEFT JOIN country ON country.country_id = zone.country_id
		
		WHERE 1 = 1
		
		@IF !empty(:country_id) 
		THEN			
			AND zone.country_id = :country_id
		END @IF				
		
		@IF !empty(:status) 
		THEN			
			AND zone.status = :status
		END @IF		
		
		@IF !empty(:limit) 
		THEN			
			LIMIT :start, :limit
		END @IF
		;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(zone.zone_id, zone) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;		
			
	END	
	
	-- get zone

	PROCEDURE get(
		IN zone_id INT,
		OUT fetch_row, 
	)
	BEGIN
		-- zone
		SELECT *
			FROM zone as _ WHERE zone_id = :zone_id;
	END
	
	-- add zone

	PROCEDURE add(
		IN zone ARRAY,
		IN language_id INT,
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		:zone_data  = @FILTER(:zone, zone);
		
		
		INSERT INTO zone 
			
			( @KEYS(:zone_data) )
			
	  	VALUES ( :zone_data );

	END
	
	-- edit zone
	CREATE PROCEDURE edit(
		IN zone ARRAY,
		IN zone_id INT,
		OUT affected_rows
	)
	BEGIN

		-- allow only table fields and set defaults for missing values
		@FILTER(:zone, zone);

		UPDATE zone 
			
			SET @LIST(:zone) 
			
		WHERE zone_id = :zone_id


	END
	
	-- delete zone

	PROCEDURE delete(
		IN zone_id ARRAY,
		OUT affected_rows, 
	)
	BEGIN
		-- zone
		DELETE FROM `zone` WHERE zone_id IN (:zone_id);
	END
