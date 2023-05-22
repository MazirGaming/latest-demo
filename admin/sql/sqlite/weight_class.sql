-- Currencies

	-- get all stock status

	PROCEDURE getAll(
		IN language_id INT,
		IN start INT,
		IN limit INT,
		OUT fetch_all, 
		OUT fetch_one,
	)
	BEGIN
		-- weight_class
		SELECT *
			FROM weight_class AS weight_class
		INNER JOIN weight_class_content	ON weight_class_content.weight_class_id = weight_class.weight_class_id
		WHERE 1 = 1
			
		@IF !empty(:language_id) 
		THEN			
			AND weight_class_content.language_id = :language_id
		END @IF
		
		LIMIT :start, :limit;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(weight_class.weight_class_id, weight_class) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;		
			
	END	
	
	-- get weight_class

	PROCEDURE get(
		IN weight_class_id INT,
		IN language_id INT,
		OUT fetch_row, 
	)
	BEGIN
		-- weight_class
		SELECT *
			FROM weight_class as _ 
		INNER JOIN weight_class_content	ON weight_class_content.weight_class_id = _.weight_class_id
		WHERE _.weight_class_id = :weight_class_id

		@IF !empty(:language_id) 
		THEN			
			AND weight_class_content.language_id = :language_id
		END @IF
		
		;
	END
	
	-- add weight_class

	PROCEDURE add(
		IN weight_class ARRAY,
		IN language_id INT,
		OUT insert_id
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		:weight_class_data  = @FILTER(:weight_class, weight_class);
		
		INSERT INTO weight_class 
			
			( @KEYS(:weight_class_data) )
			
	  	VALUES ( :weight_class_data);

		-- allow only table fields and set defaults for missing values
		:weight_class_content_data  = @FILTER(:weight_class, weight_class_content);
		
		INSERT INTO weight_class_content 
			
			( @KEYS(:weight_class_content_data), `language_id`, `weight_class_id` )
			
	  	VALUES ( :weight_class_content_data, :language_id, @result.weight_class);

	END
	
	-- edit weight_class
	CREATE PROCEDURE edit(
		IN weight_class ARRAY,
		IN weight_class_id INT,
		OUT affected_rows,
		OUT affected_rows
	)
	BEGIN

		-- allow only table fields and set defaults for missing values
		:weight_class_data  = @FILTER(:weight_class, weight_class);

		UPDATE weight_class 
			
			SET @LIST(:weight_class_data) 
			
		WHERE weight_class_id = :weight_class_id;
		
		-- allow only table fields and set defaults for missing values
		:weight_class_content_data  = @FILTER(:weight_class, weight_class_content);

		UPDATE weight_class_content 
			
			SET @LIST(:weight_class_content_data) 
			
		WHERE weight_class_id = :weight_class_id AND language_id = :language_id;


	END
	
	-- delete weight_class

	PROCEDURE delete(
		IN weight_class_id ARRAY,
		OUT affected_rows, 
		OUT affected_rows, 
	)
	BEGIN
		-- weight_class
		DELETE FROM `weight_class_content` WHERE weight_class_id IN (:weight_class_id);
		-- weight_class_content
		DELETE FROM `weight_class` WHERE weight_class_id IN (:weight_class_id);
	END