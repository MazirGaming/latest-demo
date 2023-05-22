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
		-- length_class
		SELECT *
			FROM length_class AS length_class
		INNER JOIN length_class_content	ON length_class_content.length_class_id = length_class.length_class_id
		WHERE 1 = 1
			
		@IF !empty(:language_id) 
		THEN			
			AND length_class_content.language_id = :language_id
		END @IF
		
		LIMIT :limit OFFSET :start;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(length_class.length_class_id, length_class) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;		
			
	END	
	
	-- get length_class

	PROCEDURE get(
		IN length_class_id INT,
		IN language_id INT,
		OUT fetch_row, 
	)
	BEGIN
		-- length_class
		SELECT *
			FROM length_class as _ 
		INNER JOIN length_class_content	ON length_class_content.length_class_id = _.length_class_id
		WHERE _.length_class_id = :length_class_id

		@IF !empty(:language_id) 
		THEN			
			AND length_class_content.language_id = :language_id
		END @IF
		
		;
	END
	
	-- add length_class

	PROCEDURE add(
		IN length_class ARRAY,
		IN language_id INT,
		OUT insert_id
		OUT affected_rows
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		:length_class_data  = @FILTER(:length_class, length_class);
		
		INSERT INTO length_class 
			
			( @KEYS(:length_class_data) )
			
	  	VALUES ( :length_class_data);

		-- allow only table fields and set defaults for missing values
		:length_class_content_data  = @FILTER(:length_class, length_class_content);
		
		INSERT INTO length_class_content 
			
			( @KEYS(:length_class_content_data), "language_id", "length_class_id" )
			
	  	VALUES ( :length_class_content_data, :language_id, @result.length_class);

	END
	
	-- edit length_class
	CREATE PROCEDURE edit(
		IN length_class ARRAY,
		IN length_class_id INT,
		OUT affected_rows,
		OUT affected_rows
	)
	BEGIN

		-- allow only table fields and set defaults for missing values
		:length_class_data  = @FILTER(:length_class, length_class);

		UPDATE length_class 
			
			SET @LIST(:length_class_data) 
			
		WHERE length_class_id = :length_class_id;
		
		-- allow only table fields and set defaults for missing values
		:length_class_content_data  = @FILTER(:length_class, length_class_content);

		UPDATE length_class_content 
			
			SET @LIST(:length_class_content_data) 
			
		WHERE length_class_id = :length_class_id AND language_id = :language_id;


	END

	-- delete length_class

	PROCEDURE delete(
		IN length_class_id ARRAY,
		OUT affected_rows, 
		OUT affected_rows, 
	)
	BEGIN
		-- length_class
		DELETE FROM "length_class_content" WHERE length_class_id IN (:length_class_id);
		-- length_class_content
		DELETE FROM "length_class" WHERE length_class_id IN (:length_class_id);
	END
