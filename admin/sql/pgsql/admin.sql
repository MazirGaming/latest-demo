-- Account


	CREATE PROCEDURE getAll(
		IN start INT,
		IN limit INT,
        IN status INT,
		IN search CHAR,
		IN post_id ARRAY,
		
		-- return array of admins for admins query
		OUT fetch_all,
		-- return admins count for count query
		OUT fetch_one
	)
	BEGIN
        
        SELECT * FROM admin AS admins WHERE 1 = 1


            @IF isset(:status)
			THEN 
				AND admins.status = :status 
        	END @IF	
			

            -- search
            @IF isset(:search)
			THEN 
				AND admins LIKE CONCAT('%',:search,'%')
        	END @IF	     
            
			
			-- limit
			@IF isset(:limit)
			THEN
				LIMIT :limit OFFSET :start
			END @IF;		

		--SELECT FOUND_ROWS() as count;
		SELECT count(*) FROM (
			
			@SQL_COUNT(admins.user_id, user) -- this takes previous query removes limit and replaces select columns with parameter user_id
			
		) as count;				
        
    END

	-- check user information

	CREATE PROCEDURE get(
		IN user CHAR,
		IN email CHAR,
		IN token CHAR,
        	IN admin_id INT,
       		IN status INT,
	        IN role_id INT,
		OUT fetch_row,
	)
	BEGIN
        
        SELECT _.*, role.permissions FROM admin AS _ 
			LEFT JOIN role ON (_.role_id = role.role_id)
		
		WHERE 1 = 1

	        @IF isset(:user)
		THEN 
			AND _.user = :user 
        	END @IF	

	        @IF isset(:email)
		THEN 
			AND _.email = :email 
        	END @IF			


	       @IF isset(:admin_id)
		THEN 
			AND _.admin_id = :admin_id 
        	END @IF			

	       @IF isset(:status)
		THEN 
			AND _.status = :status 
        	END @IF	            
		
		@IF isset(:token)
		THEN 
			AND _.token = :token 
        	END @IF	
			
        	@IF isset(:role_id)
		THEN 
			AND _.role_id = :role_id 
        	END @IF	
			
		LIMIT 1;
        
    END
    
    

	-- Add new admin

	CREATE PROCEDURE add(
		IN admin ARRAY,
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		@FILTER(:admin, admin);
		
		INSERT INTO admin 
			
			( @KEYS(:admin) )
			
	  	VALUES ( :admin )	 
	END    
    

	-- Update admin 
	
	CREATE PROCEDURE edit(
		IN user CHAR,
		IN email CHAR,
       	IN admin_id INT,
		IN admin ARRAY,
		IN role_id INT,
		OUT affected_rows
	)
	BEGIN
		-- allow only table fields and set defaults for missing values
		@FILTER(:admin, admin);

		UPDATE admin 
			
			SET @LIST(:admin) 
			
		WHERE 

        @IF isset(:email)
		THEN 
			email = :email 
        END @IF			

        @IF isset(:admin_id)
		THEN 
			admin_id = :admin_id 
        END @IF					

		@IF isset(:username)
		THEN 
			username = :username 
        	END @IF
	END

	-- delete admin

	PROCEDURE delete(
		IN  admin_id ARRAY,
		OUT affected_rows
	)
	BEGIN

		DELETE FROM "admin" WHERE admin_id IN (:admin_id);
		
	END	
	
	-- set role

	CREATE PROCEDURE setRole(
        IN admin_id INT,
        IN role CHAR,
        IN role_id INT
        OUT insert_id
	)
	BEGIN
		
	
		UPDATE admin 
			
			SET  
            
            @IF isset(:role_id)
			THEN 
				role_id = :role_id 
        	END @IF		


            @IF isset(:role)
			THEN 
				role_id = (SELECT role_id FROM roles WHERE name = :role)
        	END @IF		

			
		WHERE admin_id = :admin_id 
    END
