[data-v-component-reviews] [data-v-review]|deleteAllButFirstChild

[data-v-component-reviews]|prepend = <?php
if (isset($_reviews_idx)) $_reviews_idx++; else $_reviews_idx = 0;

$previous_component = isset($current_component)?$current_component:null;
$reviews = $current_component = $this->_component['reviews'][$_reviews_idx] ?? [];

$_pagination_count = $reviews['count'] ?? 0;
$_pagination_limit = isset($reviews['limit']) ? $reviews['limit'] : 5;
?>


[data-v-component-reviews]  [data-v-review]|before = <?php
if($reviews && is_array($reviews['reviews'])) 
{
	//$pagination = $this->reviews[$_reviews_idx]['pagination'];
	foreach ($this->reviews[$_reviews_idx]['reviews'] as $index => $review) 
	{
		//var_dump($review);
	?>
	
	[data-v-component-reviews] [data-v-review] [data-v-review-*] = 
    <?php 
        if (isset($review['@@__[data-v-review-*]:data-v-review-(*)__@@'])) 
            echo $review['@@__[data-v-review-*]:data-v-review-(*)__@@'];
    ?>
    
    [data-v-component-reviews] [data-v-review] [data-v-review-url] = <?php 
        echo Vvveb\url(['module' => 'review/review', 'review_id' => $review['review_id']]);
    ?>
	
	[data-v-component-reviews]  [data-v-review]|after = <?php 
	} 
}
?>
