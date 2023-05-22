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
		-- return_action
		SELECT *
			FROM return_action AS return_action WHERE 1 = 1
			
		@IF !empty(:language_id) 
		THEN			
			AND `language_id` = :language_id
		END @IF
		
		LIMIT :start, :limit;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(return_action.return_action_id, return_action) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;		
			
	END	
	
	-- get return_action

	PROCEDURE get(
		IN return_action_id INT,
		OUT fetch_row, 
	)
	BEGIN
		-- return_action
		SELECT *
			FROM return_action as _ WHERE return_action_id = :return_action_id;
	END
	
	-- add return_action

	PROCEDURE add(
		IN return_action ARRAY,
		IN language_id INT,
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		:return_action_data  = @FILTER(:return_action, return_action);
		
		
		INSERT INTO return_action 
			
			( @KEYS(:return_action_data), `language_id` )
			
	  	VALUES ( :return_action_data, :language_id );

	END
	
	-- edit return_action
	CREATE PROCEDURE edit(
		IN return_action ARRAY,
		IN return_action_id INT,
		OUT affected_rows
	)
	BEGIN

		-- allow only table fields and set defaults for missing values
		@FILTER(:return_action, return_action);

		UPDATE return_action 
			
			SET @LIST(:return_action) 
			
		WHERE return_action_id = :return_action_id


	END
	
	-- delete return_action

	PROCEDURE delete(
		IN return_action_id ARRAY,
		OUT affected_rows, 
	)
	BEGIN
		-- return_action
		DELETE FROM `return_action` WHERE return_action_id IN (:return_action_id);
	END
