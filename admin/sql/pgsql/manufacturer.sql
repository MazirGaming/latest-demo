-- Categories

	-- get one manufacturer



	CREATE PROCEDURE get(
		IN manufacturer_id INT,
		OUT fetch_row, 
	)
	BEGIN
		-- manufacturer
		SELECT *
			FROM manufacturer as _ -- (underscore) _ means that data will be kept in main array
		WHERE manufacturer_id = :manufacturer_id LIMIT 1;

		 --SELECT *,manufacturer_option_id as _ 
			--FROM manufacturer_option  WHERE manufacturer_id = :manufacturer_id;
			--@EACH(manufacturer_option, manufacturer_option_value) 
				--SELECT *, manufacturer_option_value_id as _ FROM manufacturer_option_value pov 
					--WHERE manufacturer_option_id = :manufacturer_option[manufacturer_option_id];

	END
	



	-- Edit manufacturer

	CREATE PROCEDURE edit(
		IN manufacturer ARRAY,
		IN manufacturer_id INT,
		OUT affected_rows
	)
	BEGIN

		--SELECT * FROM manufacturer_option WHERE manufacturer_id = :manufacturer_id;

		-- allow only table fields and set defaults for missing values
		@FILTER(:manufacturer, manufacturer);
		
		UPDATE manufacturer 
			
			SET @LIST(:manufacturer) 
			
		WHERE manufacturer_id = :manufacturer_id
	END	



-- Add new manufacturer

	CREATE PROCEDURE add(
		IN manufacturer ARRAY,
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		:manufacturer  = @FILTER(:manufacturer, manufacturer);

		INSERT INTO manufacturer 
		
			( @KEYS(:manufacturer) )
			
		VALUES ( :manufacturer );
		
		INSERT INTO manufacturer_to_site 
		
			( manufacturer_id, site_id )
			
		VALUES ( @result.manufacturer, :site_id );
			

	END


	-- get all manufacturers 

	CREATE PROCEDURE getAll(

		-- variables
		IN  language_id INT,
		IN  user_group_id INT,
		IN  site_id INT,
		IN  search CHAR,
		
		-- pagination
		IN  start INT,
		IN  limit INT,
			
		-- return array of manufacturers for manufacturers query
		OUT fetch_all,
		-- return manufacturers count for count query
		OUT fetch_one,
	)
	BEGIN

		SELECT * FROM manufacturer AS manufacturer
		
			LEFT JOIN manufacturer_to_site p2s ON (manufacturer.manufacturer_id = p2s.manufacturer_id) 
			WHERE p2s.site_id = :site_id

			@IF isset(:search)
			THEN 
			
				AND name LIKE :search
				
			END @IF			

			
		LIMIT :limit OFFSET :start;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(manufacturer.manufacturer_id, manufacturer) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;


	END


	-- delete manufacturer

	CREATE PROCEDURE delete(
		IN  manufacturer_id ARRAY,
		OUT affected_rows
	)
	BEGIN

		DELETE FROM manufacturer WHERE manufacturer_id IN (:manufacturer_id)
	 
	END
