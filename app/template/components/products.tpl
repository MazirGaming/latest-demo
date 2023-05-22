//set selector prefix to have shorter and easier to read selectors for rules
@products = [data-v-component-products]
@product  = [data-v-component-products] [data-v-product]
@product-image = [data-v-product-images] [data-v-product-image]

//we only need the first product element to iterate
@product|deleteAllButFirstChild

//set component variables
@products|prepend = <?php
	//use a counter to know which component instance we need to use if there are more than one component on page
	if (isset($_products_idx)) $_products_idx++; else $_products_idx = 0;
	$previous_component = isset($current_component)?$current_component:null;
	$products = $current_component = $this->_component['products'][$_products_idx] ?? [];

	$_pagination_count = $products['count'] ?? 0;
	$_pagination_limit = isset($products['limit'])? $products['limit'] : 5;
?>

@products [data-v-category] = <?php $_category = current($products['products']);echo $_category['category'];?>
@products [data-v-count] = $_pagination_count
@products [data-v-manufacturer] = <?php $_manufacturer = current($products['products']);echo $_manufacturer['manufacturer'];?>


@product|before = <?php
//if page loaded in editor then set a fist empty product if there are no products 
//to render an empty product to avoid losing the html on edit
$vvveb_is_page_edit = Vvveb\isEditor();
$_default = (isset($vvveb_is_page_edit) && $vvveb_is_page_edit ) ? [0 => []] : false;
//$_default = [0 => []];
$_products = empty($products['products']) ? $_default : $products['products'];

if ($_products) {
	foreach ($_products as $index => $product) { 
	?>
	
	@product [data-v-product-*]|innerText = $product['@@__data-v-product-(*)__@@']

	//editor attributes
	@product|data-v-id = $product['product_id']
	@product|data-v-type = 'product'
	
	//title attributes
	@product [data-v-product-title]|title = $product['name']	
	@product [data-v-product-alt]|alt = $product['name']	
	
	@product [data-product_id]|data-product_id = $product['product_id']

	//url
	@product [data-v-product-url]|title = $product['title']	
	@product a[data-v-product-url]|href = $product['url']	

	//image
	//@product [data-v-product-image]|src = $product['images'][0]['image']
	//@product [data-v-product-image]|data-v-id = $product['images'][0]['id']
	//@product [data-v-product-image]|data-v-type = 'product_image'
	
	@product [data-v-product-image]|src = <?php 
		$size = '@@__data-v-product-image__@@';
		if ($size) {
			//echo imageSize($product['image'], $size);
			//echo str_replace('.',$size,$product['image']);
			echo $product['image'];
		} else {
			echo $product['image'];
		}
	?>
	
	@product [data-v-product-image-url] = $product['image']
	//@product [data-v-product-image]|data-v-id = 'image'
	//@product [data-v-product-image]|data-v-type = 'product_image'
	
	//usually used for second image to show hover [data-v-product-image-0] [data-v-product-image-1] 
	//@product [data-v-product-image-*]|src = $product['images']['@@__data-v-product-image-(\d+)__@@']['image']
	
	@product [data-v-product-image-*]|src =  <?php 
		$size = '@@__data-v-product-image-\d+__@@';
		$nr = '@@__data-v-product-image-(\d+)__@@';
		if ($size) {
			//echo imageSize($product['image'], $size);
			echo $product['images'][$nr]['image'];
		} else {
			echo $product['images'][$nr]['image'] ?? '';
		}
	?>
	

	
	@product [data-v-product-image-*]|data-v-id = $product['images']['@@__data-v-product-image-(\d+)__@@']['id']
	@product [data-v-product-image-*]|data-v-type = 'product_image'
	
	//image gallery
	@product [data-v-product-images] [data-v-product-image]|before = <?php
	if(isset($product['images']) && is_array($product['images']))
	foreach ($product['images'] as $product_image_id => $image)  {
	?>

		@product [data-v-product-images] img[data-v-product-image-src]|src = $image['image']
		@product [data-v-product-images] img[data-v-product-image-src]|data-v-id  = $product_image_id
		@product [data-v-product-images] img[data-v-product-image-src]|data-v-type = 'product_image'
		@product [data-v-product-images] a[data-v-product-image-src]|href = $image['image']
		
		@product [data-v-product-images] [data-v-product-image]|after = <?php 
	} ?>

    //catch all data attributes
    @product [data-v-product-*]|innerText = $product['@@__data-v-product-(*)__@@']
	//echo description directly to avoid htmlentities escape
	@product [data-v-product-content] = <?php echo $product['content'];?>	
	
	@product|after = <?php 
	}
}

$current_component = $previous_component;
?>