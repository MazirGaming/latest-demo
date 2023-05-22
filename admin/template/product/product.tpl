import(crud.tpl, {"type":"product"})

[data-v-product] .autocomplete|data-text = <?php 
	$text = '@@__data-v-product-(*)__@@_text';
	$value = $this->$text ?? '';
	echo $value;
?>

/* template */
[data-v-product] select[data-v-templates]|before = <?php $optgroup = '';?>
@templates-select-option = [data-v-product] select[data-v-templates] [data-v-option]

@templates-select-option|deleteAllButFirstChild

[data-v-product] select[data-v-templates]|before = 
<?php
	 //set select name
	 $selected = '';	
	 //$name = '@@__data-v-product-(*)__@@';
	 $name = 'templates';
	 if (isset($_POST[$name])) {
		 $selected = $_POST[$name];
	 } else
	 if (isset($this->product[$name])) {
		$selected = $this->product[$name];
	 }
?>

@templates-select-option|before = <?php
	if (isset($this->$name)) {
	$options = 	$this->$name;
	foreach($options as $key => $option){?>
	
		@templates-select-option|value = $option
		@templates-select-option = <?php echo ucfirst($option);?>

@templates-select-option|after = <?php
}}?>

@templates-select-option|addNewAttribute = <?php if ($option == $selected) echo 'selected';?>


@templates-select-option|before = <?php
if ($optgroup != $option['folder']) {
	$optgroup = $option['folder'];
	echo '<optgroup label="' . ucfirst($optgroup) . '">';
}
?>

@templates-select-option|after = <?php
if ($optgroup != $option['folder']) {
	$optgroup = $option['folder'];
	echo "/<optgroup>";
}
?>

@templates-select-option|value = <?php echo $option['file'];?>
@templates-select-option|addNewAttribute = <?php if (isset($this->product['template']) && ($option['file'] == $this->product['template'])) echo 'selected';?>
@templates-select-option = <?php echo ucfirst($option['title']);?>

/* category */

[data-v-product-categories] [data-v-product-category]|deleteAllButFirstChild 

[data-v-product-categories] [data-v-product-category]|before = 
<?php
	if (isset($this->product_categories))
	foreach($this->product_categories as $category)
	{
?>

	[data-v-product-category-name] = <?php echo $category['name'];?>

[data-v-product-categories] [data-v-product-category]|after = <?php 
	}
?>



/* language tabs */
[data-v-languages]|before = <?php $_lang_instance = '@@__data-v-languages__@@';$_i = 0;?>
[data-v-languages] [data-v-language]|deleteAllButFirstChild
//[data-v-languages] [data-v-language]|addClass = <?php if ($_i == 0) echo 'active';?>

[data-v-languages] [data-v-language]|before = <?php

foreach ($this->languagesList as $language) {
?>
	[data-v-languages] [data-v-language-id]|id = <?php echo 'lang-' . $language['code'] . '-' . $_lang_instance;?>
	[data-v-languages]  [data-v-language-id]|addClass = <?php if ($_i == 0) echo 'show active';?>

	[data-v-languages] [data-v-language] [data-v-language-name] = $language['name']
	[data-v-languages] [data-v-language] [data-v-language-img]|title = $language['name']
	[data-v-languages] [data-v-language] [data-v-language-img]|src = <?php echo 'language/' . $language['code'] . '/' . $language['code'] . '.png';?>
	[data-v-languages] [data-v-language] [data-v-language-link]|href = <?php echo '#lang-' . $language['code'] . '-' . $_lang_instance?>
	[data-v-languages] [data-v-language] [data-v-language-link]|addClass = <?php if ($_i == 0) echo 'active';?>

[data-v-languages] [data-v-language]|after = <?php 
$_i++;
}
?>


[data-v-product] input[data-v-product-content-*]|name = <?php echo 'product_content[' . $language['language_id'] . '][@@__data-v-product-content-(*)__@@]';?>
[data-v-product] textarea[data-v-product-content-*]|name = <?php echo 'product_content[' . $language['language_id'] . '][@@__data-v-product-content-(*)__@@]';?>

[data-v-product] input[data-v-product-content-*]|value = <?php
	$desc = '@@__data-v-product-content-(*)__@@';
	if (isset($this->product['product_content'][$language['language_id']][$desc])) 
		echo $this->product['product_content'][$language['language_id']][$desc];
?>

[data-v-product] textarea[data-v-product-content-*] = <?php
	$desc = '@@__data-v-product-content-(*)__@@';
	if (isset($this->product['product_content'][$language['language_id']][$desc])) 
		echo $this->product['product_content'][$language['language_id']][$desc];
?>

[data-v-product] input[data-v-product-content-language_id]|value = <?php echo $language['language_id']; ?>


[data-v-product] [data-v-image]|data-v-image = $this->product['image_url']
[data-v-product] input[data-v-image]|value = $this->product['image']
[data-v-product] [data-v-image]|src = <?php echo $this->product['image_url'] ? $this->product['image_url'] : 'img/placeholder.svg';?>

//image gallery
@image-gallery = [data-v-product] [data-v-images] [data-v-image]
@image-gallery|deleteAllButFirst

@image-gallery|before = <?php
if(isset($this->product['images']) && is_array($this->product['images']))
foreach ($this->product['images'] as $product_image_id => $image)  {
	$gallery_id    = "gallery-image-$product_image_id";
	$gallery_input = "gallery-image-$product_image_id-input";

	$gallery_hash    = "#$gallery_id";
	$gallery_input_hash = "#$gallery_input";
?>
	@image-gallery [data-v-image-src]|src = $image['image']
	@image-gallery [data-v-image-src]|id = $gallery_id
	
	@image-gallery [data-v-image-src]|data-target-input = $gallery_input_hash
	@image-gallery [data-v-image-src]|data-target-thumb = $gallery_hash
	
	@image-gallery [data-v-image-btn]|data-target-input = $gallery_input_hash
	@image-gallery [data-v-image-btn]|data-target-thumb = $gallery_hash
	
	@image-gallery [name="product_image[]"] = $image['image']
	@image-gallery [name="product_image[]"]|data-target-input = $gallery_input_hash
	@image-gallery [name="product_image[]"]|id = $gallery_input
	
@image-gallery|after = <?php 
}
?>


[data-v-product] [data-v-add_to_cart]|href = <?php echo htmlentities(Vvveb\url(['module' => 'checkout/cart', 'product_id' => $product['product_id']]));?>


//attributes

[data-v-product] [data-v-attributes] [data-v-group]|deleteAllButFirst
[data-v-product] [data-v-attributes] [data-v-attribute]|deleteAllButFirst

 
[data-v-product] [data-v-url]|href = $this->product['url']
[data-v-product] [data-v-url] = $this->product['url']

[data-v-product] [data-v-design_url]|href = $this->product['design_url']

[data-v-template_missing] = <?php echo $this->template_missing;?>

import(product/product_taxonomy.tpl)
