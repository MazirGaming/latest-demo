@reviews = [data-v-component-product-reviews]
@review = [data-v-component-product-reviews] [data-v-review]

@review|deleteAllButFirstChild

@reviews|prepend = <?php
if (isset($_reviews_idx)) $_reviews_idx++; else $_reviews_idx = 0;
$reviews = [];
if(isset($this->_component['product_reviews']) && is_array($this->_component['product_reviews'][$_reviews_idx]['product_review'])) {
	$reviews = $this->_component['product_reviews'][$_reviews_idx];
}
?>

@reviews [data-v-product-reviews-*]|innerText = $reviews['@@__data-v-product-reviews-(*)__@@']

@review|before = <?php
if($reviews) {
	foreach ($reviews['reviews'] as $index => $review) {?>
	
		@review [data-v-review-*]|innerText = $review['@@__data-v-review-(*)__@@']
		
		@review [data-v-review-url] = <?php echo Vvveb\url(['module' => 'review/review', 'review_id' => $review['review_id']]);?>
		
	@review|after = <?php 
	} 
}
?>