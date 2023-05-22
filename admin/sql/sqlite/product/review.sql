-- Product reviews

	
	CREATE PROCEDURE getAll(
		-- variables
		IN  language_id INT,
		IN  site_id INT,
		IN 	product_id INT,
        IN 	user_id INT,
        IN 	status INT,

		-- pagination
		IN start INT,
		IN limit INT,

		-- return
		OUT fetch_all, -- orders
		OUT fetch_one  -- count
	)
	BEGIN

		SELECT *
            FROM `product_review` AS `product_review`
		
			WHERE 1 = 1
            
            -- post
            @IF isset(:product_id)
			THEN 
				AND product_reviews.product_id  = :product_id
        	END @IF	            
            
	   -- post slug
            @IF isset(:slug)
		THEN 
			AND product_review.product_id  = (SELECT product_id FROM product_content WHERE slug = :slug LIMIT 1) 
	      END @IF

            -- user
            @IF isset(:user_id)
			THEN 
				AND product_review.user_id  = :user_id
        	END @IF	              
            
			-- user
            @IF isset(:status)
			THEN 
				AND product_review.status  = :status
        	END @IF	            

		LIMIT :start, :limit;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(product_review.product_review_id, product_review) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;
		
		
	END