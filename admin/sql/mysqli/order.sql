-- Orders

	-- get all account orders

	CREATE PROCEDURE getAll(
		-- variables
		IN  language_id INT,
		IN  site_id INT,
		IN 	user_id INT
		
		IN order_status CHAR,
		
		-- pagination
		IN start INT,
		IN limit INT,
		
		
		-- return
		OUT fetch_all, -- orders
		OUT fetch_one  -- count
	)
	BEGIN
        
        SELECT orders.*,os.name as order_status FROM `order` AS orders 
		
			LEFT JOIN order_status AS os ON (orders.order_status_id = os.order_status_id AND os.language_id = :language_id) 
			
		WHERE 1 = 1 
		
			AND orders.site_id = :site_id
			
			@IF isset(:user_id)
			THEN 
				AND orders.user_id = :user_id
			END @IF
			
			@IF isset(:order_status)
			THEN 
				AND os.name = :order_status
			END @IF		


		LIMIT :start, :limit;
		
		SELECT count(*) FROM (
			
			@SQL_COUNT(order_id, order) -- this takes previous query removes limit and replaces select columns with parameter order_id
			
		) as count;

    END	
	
	
	
    
	CREATE PROCEDURE get(
        IN order_id INT,
		IN user_id INT,
		IN language_id INT,
		OUT fetch_row,
		OUT fetch_all,
		OUT fetch_all,
		OUT fetch_all,
		OUT fetch_all,
		OUT fetch_all,
		OUT fetch_all,
		OUT fetch_all,
	)
	BEGIN
        
        SELECT * FROM `order` WHERE  1 = 1
			
		@IF isset(:user_id)
		THEN 
			AND `order``.user_id = :user_id
		END @IF

		AND `order`.order_id = :order_id;
		
        	
		SELECT `key` as array_key,`value` as array_value FROM order_meta as _
			WHERE _.order_id = :order_id;
            
	
		SELECT *,products.name as name, products.`product_id` as array_key FROM order_product as products
			LEFT JOIN product ON product.product_id = products.product_id	
			LEFT JOIN product_content ON product_content.product_id = products.product_id	
			LEFT JOIN order_option ON products.order_product_id = order_option.order_product_id	

			@IF isset(:language_id)
			THEN 
				AND product_content.language_id = :language_id
			END @IF


		WHERE products.order_id = :order_id;		
			
			
		SELECT * FROM order_history as history
			WHERE history.order_id = :order_id;		
		
		SELECT * FROM order_meta as meta
			WHERE meta.order_id = :order_id;
        
		SELECT * FROM order_total as total
			WHERE total.order_id = :order_id;		
			
		SELECT * FROM order_shipment as shipment
			WHERE shipment.order_id = :order_id;		
			
		SELECT * FROM order_voucher as voucher
			WHERE voucher.order_id = :order_id;
        
        
    END    
    
	-- delete order
	
	PROCEDURE delete(
		IN  order_id ARRAY,
		OUT affected_rows
	)
	BEGIN

		DELETE FROM order_product WHERE order_id IN (:order_id);
		DELETE FROM order_history WHERE order_id IN (:order_id);
		DELETE FROM order_option WHERE order_id IN (:order_id);
		DELETE FROM order_recurring_transaction WHERE order_recurring_id IN (SELECT order_recurring_id FROM order_recurring WHERE order_id IN (:order_id));
		DELETE FROM order_recurring WHERE order_id IN (:order_id);
		DELETE FROM order_shipment WHERE order_id IN (:order_id);
		DELETE FROM order_voucher WHERE order_id IN (:order_id);
		DELETE FROM order_total WHERE order_id IN (:order_id);
		DELETE FROM voucher_history WHERE order_id IN (:order_id);
		DELETE FROM voucher WHERE order_id IN (:order_id);
		DELETE FROM `order` WHERE order_id IN (:order_id);
		
	END	
	
	
	-- add order

	CREATE PROCEDURE add(
		IN order ARRAY,
		OUT insert_id,
		OUT insert_id
		OUT insert_id
	)
	BEGIN

		:products  = @FILTER(:order.products, order_product, false, true);
		:totals  = @FILTER(:order.totals, order_total, false, true);
		@FILTER(:order, order);
		
		INSERT INTO `order` 
			
			( @KEYS(:order) )
			
	  	VALUES ( :order );
		
		-- insert order products
		@EACH(:products) 
			INSERT INTO order_product 
				( `order_id`, `reward`, @KEYS(:each) )
			VALUES ( @result.order, 0,  :each  );

		-- insert order products
		@EACH(:totals) 
			INSERT INTO order_total 
				( `order_id`, @KEYS(:each) )
			VALUES ( @result.order, :each  );

    END
    

