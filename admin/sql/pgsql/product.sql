-- Products

	-- get one product

	PROCEDURE get(
		IN product_id INT,
		IN slug CHAR,
		IN language_id INT,
		OUT fetch_row, 
		OUT fetch_all, 
		OUT fetch_all, 
	)
	BEGIN
		-- product
		SELECT pd.*,_.*
			FROM product as _ -- (underscore) _ means that data will be kept in main array
			LEFT JOIN product_content pd ON (
				_.product_id = pd.product_id
		
				@IF isset(:language_id)
				THEN
					AND pd.language_id = :language_id
				END @IF
			)  			
	
		WHERE  1 = 1

            @IF isset(:slug)
			THEN 
				AND pd.slug = :slug 
        	END @IF			

            @IF isset(:product_id)
			THEN 
                AND _.product_id = :product_id
        	END @IF		
        
        LIMIT 1;

		--description, use @result to make sure we have a product_id if the product is selected by slug
		SELECT *,language_id, language_id as array_key -- (underscore) _ column means that this column (language_id) value will be used as array key when adding row to result array
			FROM product_content 
		WHERE product_id = @result.product_id;	 

		--images
		SELECT image, product_image_id as id, sort_order -- , product_image_id as array_key -- product_image_id will be used as key
			FROM product_image AS images
		WHERE product_id = @result.product_id ORDER BY sort_order;

		 --SELECT *,product_option_id as _ 
			--FROM product_option  WHERE product_id = :product_id;
			--@EACH(product_option, product_option_value) 
				--SELECT *, product_option_value_id as _ FROM product_option_value pov 
					--WHERE product_option_id = :product_option[product_option_id];

	END
	

	PROCEDURE getData(
		IN product_id INT,
		IN manufacturer_id INT,
		IN vendor_id INT,
		
		OUT fetch_all, 
		OUT fetch_all, 
		OUT fetch_all, 
		OUT fetch_all, 
		
		OUT fetch_one, 
		OUT fetch_one, 
	)
	BEGIN
	
		-- tax_class
		SELECT 
		
			name,  tax_class_id as array_key, 
			name as array_value
			
			FROM tax_class as tax_class_id; -- (underscore) _ means that data will be kept in main array
		
		
		-- weight_class
		SELECT 
		
			*, weight_class_id.weight_class_id as array_key,
			weight_desc.name as array_value -- only set name as value and return 
			
		FROM weight_class as weight_class_id
			LEFT JOIN weight_class_content as weight_desc
				ON weight_class_id.weight_class_id = weight_desc.weight_class_id; -- (underscore) _ means that data will be kept in main array
			
		-- length_class
		SELECT 
		
			*, length_class_id.length_class_id as array_key,
			length_desc.name as array_value -- only set name as value and return 
			
		FROM length_class as length_class_id
			LEFT JOIN length_class_content as length_desc
				ON length_class_id.length_class_id = length_desc.length_class_id; -- (underscore) _ means that data will be kept in main array
			
			
		-- stock status	
		SELECT 
		
			stock_status_id as array_key, -- tax_class_id as key
			name as array_value -- only set name as value and return  
			
		FROM stock_status as stock_status_id;

		SELECT name FROM manufacturer AS manufacturer_id_text WHERE manufacturer_id = :manufacturer_id LIMIT 1;
		
		SELECT name FROM vendor AS vendor_id_text WHERE vendor_id = :vendor_id LIMIT 1;
		
		--<?php
		--	:results['status'] = [];
		--?>


	END
	
	
	-- Delete product

	PROCEDURE delete(
		IN  product_id ARRAY,
		OUT affected_rows
	)
	BEGIN

		DELETE FROM product_to_site WHERE product_id IN (:product_id);
		DELETE FROM product_image WHERE product_id IN (:product_id);
		DELETE FROM product_content WHERE product_id IN (:product_id);
		DELETE FROM product WHERE product_id IN (:product_id);
		
	END	
	
	-- Edit product

	PROCEDURE edit(
		IN product ARRAY,
		IN  product_id INT,
		OUT insert_id
	)
	BEGIN
		
		@EACH(:product.product_content) 
			INSERT INTO product_content 
		
				( @KEYS(:each), product_id, meta_title, meta_description, meta_keyword )
			
			VALUES ( :each, :product_id, '', '', '' )

			ON CONFLICT("product_id", "language_id") DO UPDATE SET @LIST(:each);


			--SELECT * FROM product_option WHERE product_id = :product_id;
		

		--SELECT * FROM product_option WHERE product_id = :product_id;

		-- allow only table fields and set defaults for missing values
		:product_update  = @FILTER(:product, product, false);

		
		
		UPDATE product 
			
			SET @LIST(:product_update) 
			
		WHERE product_id = :product_id
	
		
		
	END	


-- Add new product

	CREATE PROCEDURE add(
		IN product ARRAY,
		OUT insert_id,
		OUT insert_id
	)
	BEGIN
		
		-- allow only table fields and set defaults for missing values
		:product_data  = @FILTER(:product, product);
		
		INSERT INTO product 
		
			( @KEYS(:product_data) )
			
		VALUES ( :product_data );
			

		:product_content = @FILTER(:product.product_content, product_content, false, true)

		@EACH(:product_content) 
			INSERT INTO product_content 
		
				( @KEYS(:each), product_id, meta_title, meta_description, meta_keyword )
			
			VALUES ( :each, @result.product, '', '', '' );
		
		
		-- UPDATE product SET image = :image WHERE product_id = :product_id;
		
		-- :product  = @FILTER(:product_data, product);
		
		-- INSERT INTO product_content SET product_id = '52', language_id = '1', name = 'test 2', description = '&lt;p&gt;test 2&lt;br&gt;&lt;/p&gt;', tag = '', meta_title = 'test 2', meta_description = '', meta_keyword = ''
		
		INSERT INTO product_to_site SET product_id = @result.product, site_id = :site_id
	 
	END


	-- get all products 

	PROCEDURE getAll(

		-- variables
		IN language_id INT,
		IN user_group_id INT,
		IN site_id INT,
		IN product_id ARRAY,
		IN taxonomy_item_id INT,
		IN search CHAR,
		IN slug ARRAY,
		
		-- pagination
		IN start INT,
		IN limit INT,
		IN type CHAR,
		IN order_by CHAR,
		IN direction CHAR,
		
		-- columns options (local variables used for conditional sql)
		LOCAL include_manufacturer INT,
		LOCAL include_discount INT,
		LOCAL include_special INT,
		LOCAL include_reward INT,
		LOCAL include_stock_status INT,
		LOCAL include_image_gallery INT,
			
		-- return array of products for products query
		OUT fetch_all,
		-- return products count for count query
		OUT fetch_one,
	)
	BEGIN

		SELECT  pd.*,products.*

				@IF !empty(:include_manufacturer) 
				THEN 
					,m.name AS manufacturer
				END @IF
				

			-- include image gallery 	
			
			@IF !empty(:include_image_gallery) 
			THEN 
				--,(SELECT '[' || STRING_AGG('{"id":"' || pi.product_image_id || '","image":"' || pi.image || '"}') || ']' FROM product_image as pi WHERE pi.product_id = products.product_id GROUP BY pi.product_id) as images
				,(SELECT json_agg(json_build_object('id',pi.product_image_id,'image',pi.image)) FROM product_image as pi WHERE pi.product_id = products.product_id GROUP BY pi.product_id) as images
			END @IF

			-- include discount 	
			@IF !empty(:include_discount) && !empty(:user_group_id) 
			THEN 
			
				 ,(SELECT price
				   FROM product_discount pd2
				   WHERE pd2.product_id = products.product_id
					 AND pd2.user_group_id = :user_group_id
					 AND pd2.quantity = '1'
					 AND ((pd2.date_start = '0000-00-00'
						   OR pd2.date_start < NOW())
						  AND (pd2.date_end = '0000-00-00'
							   OR pd2.date_end > NOW()))
				   ORDER BY pd2.priority ASC, pd2.price ASC
				   LIMIT 1) AS discount
				   
			END @IF
			
			-- include special price 	
			@IF !empty(:include_special) && !empty(:user_group_id) 
			THEN 
			
			  ,(SELECT price
			   FROM product_special ps
			   WHERE ps.product_id = products.product_id
				 AND ps.user_group_id = :user_group_id
				 AND ((ps.date_start = '0000-00-00'
					   OR ps.date_start < NOW())
					  AND (ps.date_end = '0000-00-00'
						   OR ps.date_end > NOW()))
			   ORDER BY ps.priority ASC, ps.price ASC
			   LIMIT 1) AS special
			   
			END @IF


			-- include reward 	
			@IF !empty(:include_reward) && !empty(:user_group_id) 
			THEN 
			
			  ,(SELECT points
			   FROM product_reward pr
			   WHERE pr.product_id = products.product_id
				 AND pr.user_group_id = :user_group_id
			   AS reward
			   
			END @IF
			
			-- include stock_status 	
			@IF !empty(:include_stock_status)
			THEN 

			  ,(SELECT ss.name
			   FROM stock_status ss
			   WHERE ss.stock_status_id = products.stock_status_id
				 AND ss.language_id = :language_id) 
			  AS stock_status

			   
			END @IF


			-- include weight_class 	
			@IF !empty(:include_weight_class)
			THEN 
			
			  ,(SELECT wcd.unit
			   FROM weight_class_content wcd
			   WHERE products.weight_class_id = wcd.weight_class_id
				 AND wcd.language_id = :language_id) 
			   AS weight_class
			   
			END @IF


			-- include length_class 	
			@IF !empty(:include_length_class)
			THEN 
			
			  ,(SELECT lcd.unit
			   FROM length_class_content lcd
			   WHERE products.length_class_id = lcd.length_class_id
				 AND lcd.language_id = :language_id) 
			   AS length_class
			   
			END @IF
		
		
			-- include rating
			@IF !empty(:include_rating)
			THEN 
			
			  ,(SELECT AVG(rating) AS total
			   FROM review r1
			   WHERE r1.product_id = products.product_id
				 AND r1.status = '1'
			   GROUP BY r1.product_id) 
			  AS rating

			   
			END @IF
		
			-- include reviews
			@IF !empty(:include_reviews)
			THEN 

			  ,(SELECT COUNT(*) AS total
			   FROM review r2
			   WHERE r2.product_id = products.product_id
				 AND r2.status = '1'
			   GROUP BY r2.product_id) AS reviews
									
			   
			END @IF
		
		
			@IF isset(:search)
			THEN 
				,MATCH(pd.name, pd.content) AGAINST(:search) as score
			END @IF	

		 
		FROM product AS products
		
			LEFT JOIN product_to_site p2s ON (products.product_id = p2s.product_id) 
			LEFT JOIN product_content pd ON (
				products.product_id = pd.product_id
		
				@IF isset(:language_id)
				THEN
					AND pd.language_id = :language_id
				END @IF

			)  

			@IF !empty(:include_manufacturer) 
			THEN 
				LEFT JOIN manufacturer m ON (products.manufacturer_id = m.manufacturer_id)
			END @IF
			
			@IF !empty(:taxonomy_item_id) 
			THEN 
				INNER JOIN product_to_category pc ON (products.product_id = pc.product_id AND pc.taxonomy_item_id = :taxonomy_item_id)
			END @IF		
			
			
			WHERE p2s.site_id = :site_id

            -- search
            @IF isset(:search) && !empty(:search)
			THEN 
				-- AND pd.name LIKE CONCAT('%',:search,'%')
				AND MATCH(pd.name, pd.content) AGAINST(:search)
        	END @IF     
                       
					   
			@IF isset(:type) && !empty(:type)
			THEN  
				AND products.type = :type
        	END @IF		
			
			@IF isset(:manufacturer_id) && !empty(:manufacturer_id)
			THEN 
				AND products.manufacturer_id = :manufacturer_id
        	END @IF	   		
			
			@IF isset(:vendor_id) && !empty(:vendor_id)
			THEN 
				AND products.vendor_id = :vendor_id
        	END @IF	    			
			
			@IF isset(:price) && :price !== ""
			THEN 
				AND products.price = :price
        	END @IF	  			
			
			@IF isset(:quantity) && :quantity !== ""
			THEN 
				AND products.quantity = :quantity
        	END @IF				
			
			@IF isset(:model) && :model !== ""
			THEN 
				AND products.model = :model
        	END @IF				
			
			@IF isset(:sku) && :sku !== ""
			THEN 
				AND products.sku = :sku
        	END @IF	 
			
			@IF isset(:upc) && :upc !== ""
			THEN 
				AND products.upc = :upc
        	END @IF	 	
			
			@IF isset(:ean) && :ean !== ""
			THEN 
				AND products.ean = :ean
        	END @IF	    
			
			@IF isset(:isbn) && :isbn !== ""
			THEN 
				AND products.isbn = :isbn
        	END @IF				
			
 
            
			@IF isset(:product_id) && count(:product_id) > 0
			THEN 
			
				AND products.product_id IN (:product_id)
				
			END @IF			

			@IF isset(:slug) && count(:slug) > 0
			THEN 
			
				AND pd.slug IN (:slug)
				
			END @IF			

		
		-- ORDER BY parameters can't be binded because they are added to the query directly they must be properly sanitized by only allowing a predefined set of values
		@IF isset(:order_by)
		THEN
			ORDER BY $order_by $direction		
		END @IF		
		
		
		@IF isset(:limit)
		THEN
			LIMIT :limit OFFSET :start
		END @IF;		
		
		-- SELECT FOUND_ROWS() as count;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(products.product_id, product) -- this takes previous query removes limit and replaces select columns with parameter product_id
			
		) as count;


	END
