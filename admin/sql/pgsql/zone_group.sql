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
		-- zone_group
		SELECT *
			FROM "zone_group" AS "zone_group" WHERE 1 = 1
			
		@IF !empty(:limit) 
		THEN			
			LIMIT :limit OFFSET :start
		END @IF
		
		;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(zone_group.zone_group_id, zone_group) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;		
			
	END	
	
	
	-- get zones for zone group 

	PROCEDURE getZones(
		IN zone_group_id INT,
		IN country_id INT,
		IN start INT,
		IN limit INT,
		OUT fetch_all, 
		OUT fetch_one,
	)
	BEGIN
		-- zone
		SELECT zones.*,country.name as country, zone.name as zone
			FROM zone_to_zone_group AS zones 
		LEFT JOIN zone ON zones.zone_id = zone.zone_id
		LEFT JOIN country ON zones.country_id = country.country_id
		
		WHERE zones.zone_group_id = :zone_group_id
		
		
		@IF !empty(:country_id) 
		THEN			
			AND zone.country_id = :country_id
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

	-- add tax_rule

	PROCEDURE addZones(
		IN zone_to_zone_group ARRAY,
		IN zone_group_id INT,
		OUT insert_id
	)
	BEGIN
		-- BEGIN transaction;

		DELETE FROM `zone_to_zone_group` WHERE zone_group_id = :zone_group_id;
		
		-- allow only table fields and set defaults for missing values
		:zone_to_zone_group_data  = @FILTER(:zone_to_zone_group, zone_to_zone_group);
		
		
		@EACH(:zone_to_zone_group_data) 
		INSERT INTO `zone_to_zone_group` 
			
			( @KEYS(:each), zone_group_id )
			
	  	VALUES ( :each, :zone_group_id );
		
		-- END transaction;

	END

	-- get zone_group

	PROCEDURE get(
		IN zone_group_id INT,
		OUT fetch_row, 
	)
	BEGIN
		-- zone_group
		SELECT *
			FROM "zone_group" as _ WHERE zone_group_id = :zone_group_id;
	END
	
	-- add zone_group

	PROCEDURE add(
		IN zone_group ARRAY,
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		:zone_group_data  = @FILTER(:zone_group, zone_group);
		
		
		INSERT INTO "zone_group" 
			
			( @KEYS(:zone_group) )
			
	  	VALUES ( :zone_group );

	END
	
	-- edit zone_group
	CREATE PROCEDURE edit(
		IN zone_group ARRAY,
		IN zone_group_id INT,
		OUT affected_rows
	)
	BEGIN

		-- allow only table fields and set defaults for missing values
		@FILTER(:zone_group, zone_group);

		UPDATE "zone_group"
			
			SET @LIST(:zone_group) 
			
		WHERE zone_group_id = :zone_group_id


	END
	
	-- delete zone_group

	PROCEDURE delete(
		IN zone_group_id ARRAY,
		OUT affected_rows, 
		OUT affected_rows, 
	)
	BEGIN
		-- zone
		DELETE FROM `zone_to_zone_group` WHERE zone_group_id IN (:zone_group_id);
		DELETE FROM `zone_group` WHERE zone_group_id IN (:zone_group_id);
	END
