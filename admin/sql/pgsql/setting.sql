-- Settings

	-- get one option

	CREATE PROCEDURE getSetting(
		IN key CHAR,
		IN site_id INT,
		
		OUT fetch_one,
	)
	BEGIN

		SELECT "value"
            FROM "option" AS _
		WHERE _."key" = :key 
		
		@IF !empty(:site_id) 
		THEN 
			AND _."site_id" = :site_id
		END @IF		
		;
		
	END
    
	CREATE PROCEDURE setSetting(
		IN key CHAR,
		IN value CHAR,
        IN site_id INT,
		
		OUT insert_id
	)
	BEGIN

        INSERT INTO "option"
            ("key", "value", "site_id")
        
        VALUES ( :key, :value, :site_id )
        
        ON CONFLICT ("key", "site_id") DO UPDATE SET "value" = :value;
		
	END
    
	CREATE PROCEDURE deleteSetting(
		IN key CHAR,
	)
	BEGIN

        DELETE FROM 
            "option" 
        WHERE "key" = :key;
		
	END

    CREATE PROCEDURE getSettings(
		IN key ARRAY,
		IN site_id INT,
	)
	BEGIN

		SELECT "key", "value"
            FROM "option" AS _
		WHERE _."key"IN (:key)
		
		@IF !empty(:site_id) 
		THEN 
			AND _."site_id" = :site_id
		END @IF	
		
	END    
    
    
	CREATE PROCEDURE setSettings(
		IN option ARRAY,
		IN site_id INT,
	)
	BEGIN

        INSERT INTO "option"
            ("key", "value", "site_id")
        
		-- @EACH(:options) 
			VALUES ( :each, :site_id)
		-- END @EACH	
		
        -- @VALUES(:options) --@VALUES expands the array to the following expression
        --    ( :options.each.key, :options.each.value, :site_id )
        
        ON DUPLICATE KEY 
            UPDATE "value" = VALUES(value);
		
	END
    
	CREATE PROCEDURE deleteSettings(
		IN keys ARRAY,
		IN site_id,
	)
	BEGIN

        DELETE FROM 
            "option" 
        WHERE "key" IN (:keys) AND site_id = :site_id;
		
	END    
