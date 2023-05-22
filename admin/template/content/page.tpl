import(crud.tpl, {"type":"post"})

[data-v-post] select[data-v-post-*] option|addNewAttribute = <?php if ($selected == '@@__value__@@') echo 'checked';?>

[data-v-post-categories] [data-v-post-category]|deleteAllButFirstChild 

[data-v-post-categories] [data-v-post-category]|before = 
<?php
	if (isset($this->post_categories))
	foreach($this->post_categories as $category)
	{
?>

	[data-v-post-category-name] = <?php echo $category['name'];?>

[data-v-post-categories] [data-v-post-category]|after = <?php 
	}
?>


/* language tabs */
[data-v-languages]|before = <?php $_lang_instance = '@@__data-v-languages__@@';$_i = 0;?>
[data-v-languages] [data-v-language]|deleteAllButFirstChild
//[data-v-languages] [data-v-language]|addClass = <?php if ($_i == 0) echo 'active';?>

[data-v-languages] [data-v-language]|before = <?php
foreach ($this->languages as $language) 
{
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


[data-v-post] input[data-v-post-content-*]|name = <?php echo 'post_content[' . $language['language_id'] . '][@@__data-v-post-content-(*)__@@]';?>
[data-v-post] textarea[data-v-post-content-*]|name = <?php echo 'post_content[' . $language['language_id'] . '][@@__data-v-post-content-(*)__@@]';?>

[data-v-post] input[data-v-post-content-*]|value = <?php
	if (isset($this->post['post_content'][$language['language_id']]['@@__data-v-post-content-(*)__@@'])) 
		echo $this->post['post_content'][$language['language_id']]['@@__data-v-post-content-(*)__@@'];
?>

[data-v-post] textarea[data-v-post-content-*] = <?php
	if (isset($this->post['post_content'][$language['language_id']]['@@__data-v-post-content-(*)__@@'])) 
		echo $this->post['post_content'][$language['language_id']]['@@__data-v-post-content-(*)__@@'];
?>


[data-v-post] input[data-v-post-content-language_id]|value = <?php echo $language['language_id']; ?>


[data-v-post] [data-v-image]|data-v-image = $this->post['image_url']
[data-v-post] input[data-v-image]|value = $this->post['image']
[data-v-post] [data-v-image]|src = $this->post['image_url']