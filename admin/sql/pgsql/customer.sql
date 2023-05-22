-- Users

	-- get all account users

	CREATE PROCEDURE getAll(
		-- variables
		IN  language_id INT,
		IN  site_id INT,
		IN 	user_id INT

		-- pagination
		IN  start INT,
		IN count INT,
		
		
		-- return
		OUT fetch_all, -- orders
		OUT fetch_one  -- count
	)
	BEGIN
        
        SELECT * FROM user AS users 
		
			
		WHERE 1 = 1 
		
		@IF isset(:user_id)
		THEN 
			AND user.user_id = :user_id
		END @IF
		;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(user_id, user) -- this takes previous query removes limit and replaces select columns with parameter order_id
			
		) as count;		
        
    END
	
    
	CREATE PROCEDURE get(
		IN user_id INT,
        IN user_id INT,
	)
	BEGIN
        
        SELECT * FROM user AS "user" WHERE o.user_id = :user_id AND o.user_id = :user_id;
        
        	
		SELECT "key" as array_key,"value" as array_value FROM user_meta as _
			WHERE _.user_id = :user_id
            
	
		SELECT "product_id" as array_key FROM user_product as products
			WHERE products.user_id = :user_id
        
        
    END    
    
	-- get all account users

	CREATE PROCEDURE placeUser(
		IN user ARRAY,
	)
	BEGIN

		@FILTER(:user, user);
		
		INSERT INTO "user" 
			
			( @KEYS(:user) )
			
	  	VALUES ( :user )

		
		--@EACH(:user.products) 
			--INSERT INTO user_product 
		
				--( @KEYS(:each), user_id, meta_title, meta_description, meta_keyword )
			
			--VALUES ( :each, @result.user, '', '', '' );
    END
    
        
