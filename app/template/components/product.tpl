@product = [data-v-component-product]
@images = [data-v-component-product] [data-v-product-images]


@product|data-v-id = $product['product_id']
@product|data-v-type = 'product'

@product|before = <?php
if (isset($_product_idx)) $_product_idx++; else $_product_idx = 0;
$previous_component = isset($component)?$component:null;

$previous_component = isset($current_component)?$current_component:null;
$product = $current_component = $this->_component['product'][$_product_idx] ?? [];

$_pagination_count = $product['count'] ?? 0;
$_pagination_limit = isset($product['limit']) ? $product['limit'] : 5;
?>

//editor attributes
@product|data-v-id = $product['product_id']
@product|data-v-type = 'product'

//catch all data attributes
@product [data-v-product-*]|innerText = $product['@@__data-v-product-(*)__@@']
@product input[data-v-product-*]|value = $product['@@__data-v-product-(*)__@@']
@product a[data-v-product-*]|href = $product['@@__data-v-product-(*)__@@']

//usually used for second image to show hover [data-v-product-image-0] [data-v-product-image-1] 
@product [data-v-product-image-*]|src = $product['images']['@@__data-v-product-image-(\d+)__@@']['image']
//editor attributes
@product [data-v-product-image-*]|data-v-id = $product['images']['@@__data-v-product-image-(\d+)__@@']['id']
@product [data-v-product-image-*]|data-v-type = 'product_image'

//manual echo to avoid html escape
@product [data-v-product-content] = <?php echo $product['content'];?>

@product [data-v-product-manufacturer_url]|href = <?php 
	echo url('manufacturer', '', ['id_manufacturer' => $product['id_manufacturer'], 'manufacturer' => $product['manufacturer']]);
?>


@product img[data-v-product-main-image]|src = <?php echo $product['image'];?>
@product a[data-v-product-main-image]|href = <?php echo reset($product['images'])['image'];?>


@images [data-v-product-image]|deleteAllButFirstChild

@product [data-v-add_to_cart]|href = <?php 
	echo htmlentities(Vvveb\url(['module' => 'checkout/cart', 'product_id' => $product['product_id']]));
?>


@images [data-v-product-image]|before = <?php
if(isset($product['images']) && is_array($product['images'])) {
	$i = 0;
	foreach ($product['images'] as $index => $image) { ?>

		@images [data-bs-slide-to]|data-bs-slide-to = <?php echo $i;?>
		@images img[data-v-product-image-src]|src = $image['image']
		@images [data-v-product-image-background-image]|style = <?php echo 'background-image: url(\'' . $image['image'] . '\');';?>
		@images a[data-v-product-image-src]|href = $image['image']
		@images img[data-v-product-image-*]|data-v-id = $image['id']
		
		@images [data-v-product-image]|after = <?php 
			$i++; 
	}
}

$component = $previous_component;
?>


 