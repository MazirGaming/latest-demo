-- Addresss

	-- get all addresses 

	CREATE PROCEDURE getAll(
		-- variables
		IN site_id INT,
        IN user_id INT,

		-- pagination
		IN start INT,
		IN limit INT,

		-- return
		OUT fetch_all, -- orders
		OUT fetch_one  -- count
	)
	BEGIN

		SELECT *
		
			FROM address AS address
		WHERE 1 = 1
            
		-- user
		@IF isset(:user_id)
		THEN 
			AND address.user_id  = :user_id
		END @IF	              
            

		LIMIT :start, :limit;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(address_id, address) -- this takes previous query removes limit and replaces select columns with parameter address_id
			
		) as count;	 
	END
	

	-- get one address

	CREATE PROCEDURE get(
		IN address_id INT,
		OUT fetch_row,
	)
	BEGIN

		SELECT * 
			FROM address AS _
		WHERE 1 = 1

            @IF isset(:address_id)
			THEN
                AND _.address_id = :address_id
        	END @IF			

        LIMIT 1; 
		
		
		-- SELECT `key` as array_key,`value` as array_value FROM address_meta as _
			-- WHERE _.address_id = @result.address_id
		
          
	END

	-- Add new address

	CREATE PROCEDURE add(
		IN address ARRAY,
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		@FILTER(:address, address);
		
		INSERT INTO address 
			
			( @KEYS(:address) )
			
	  	VALUES ( :address )
        
	END

	-- Edit address

	CREATE PROCEDURE edit(
		IN address ARRAY,
		IN id_address INT,
        IN user_id INT,
		OUT affected_rows
	)
	BEGIN
		-- allow only table fields and set defaults for missing values
		@FILTER(:address, address);

		UPDATE address 
			
			SET  @LIST(:address) 
			
		WHERE address_id = :address_id
		
		@IF isset(:user_id)
		THEN
			AND user_id = :user_id
		END @IF;	 
		
	END
	
	-- Delete address

	CREATE PROCEDURE delete(
		IN  address_id ARRAY,
		IN user_id INT,
		OUT affected_rows
	)
	BEGIN

		DELETE FROM address WHERE address_id IN (:address_id) 
		
		@IF isset(:user_id)
		THEN
			AND user_id = :user_id
		END @IF;	 
	END
