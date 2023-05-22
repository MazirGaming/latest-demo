-- Product Reviews

	-- get all product reviews 

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
            		FROM "product_review" AS "product_review"
		
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
            
			-- status
            @IF isset(:status)
			THEN 
				AND product_review.status  = :status
        	END @IF	            

		LIMIT :limit OFFSET :start;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(product_review.product_review_id, product_review) -- this takes previous query removes limit and replaces select columns with parameter product_review_id
			
		) as count;	 
	END
	

	-- get one product_review

	CREATE PROCEDURE get(
		IN product_review_id INT,
		OUT fetch_row,
	)
	BEGIN

		SELECT * 
			FROM product_review AS _
		WHERE 
			
			1

            @IF isset(:product_review_id)
			THEN
                AND _.product_review_id = :product_review_id
        	END @IF			

        LIMIT 1; 
		
		
		-- SELECT "key" as array_key,"value" as array_value FROM product_review_meta as _
			-- WHERE _.product_review_id = @result.product_review_id
		
          
	END

	-- Add new product_review

	CREATE PROCEDURE add(
		IN product_review ARRAY,
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		@FILTER(:product_review, product_review);
		
		INSERT INTO product_review 
			
			( @KEYS(:product_review) )
			
	  	VALUES ( :product_review )
        
	END

	-- Edit product_review

	CREATE PROCEDURE edit(
		IN product_review ARRAY,
		IN  id_product_review INT,
		OUT insert_id
	)
	BEGIN
		-- allow only table fields and set defaults for missing values
		@FILTER(:product_review, product_review);

		UPDATE product_review 
			
			SET  @LIST(:product_review) 
			
		WHERE product_review_id = :id_product_review
	 
	END
	
	-- Delete product_review

	CREATE PROCEDURE deleteProductReview(
		IN  id_product_review INT,
	)
	BEGIN

		DELETE FROM product_review WHERE product_review_id = :id_product_review
	 
	END
