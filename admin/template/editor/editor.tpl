import(common.tpl)

//theme inputs
[data-v-theme-inputs]|deleteAllButFirstChild

[data-v-theme-inputs]|before = <?php
if(isset($this->themeComponents) && is_array($this->themeComponents)) 
{
	foreach ($this->themeComponents as $file) {?>
	
	[data-v-theme-inputs]|src = $file

	[data-v-theme-inputs]|after = <?php 
	} 
}?>


//theme components
[data-v-theme-components]|deleteAllButFirstChild

[data-v-theme-components]|before = <?php
if(isset($this->themeInputs) && is_array($this->themeInputs)) 
{
	foreach ($this->themeInputs as $file) {?>
	
	[data-v-theme-components]|src = $file

	[data-v-theme-components]|after = <?php 
	} 
}?>

//theme sections
[data-v-theme-sections]|deleteAllButFirstChild

[data-v-theme-sections]|before = <?php
if(isset($this->themeSections) && is_array($this->themeSections)) 
{
	foreach ($this->themeSections as $file) {?>
	
	[data-v-theme-sections]|src = $file

	[data-v-theme-sections]|after = <?php 
	} 
}?>


/* template list for new template modal */
select[data-v-theme-*]|before = <?php $name = '@@__data-v-theme-(*)__@@'; $optgroup = '';?>
@templates-select-option = select[data-v-theme-*] [data-v-option]
@templates-select-option|deleteAllButFirstChild

@templates-select-option|before = <?php
if (isset($this->$name)) {
	$options = 	$this->$name;
	foreach($options as $key => $option){
	
		if ($optgroup != $option['folder']) {
			$optgroup = $option['folder'];
			echo '<optgroup label="' . ucfirst($optgroup) . '">';
		}
?>

	@templates-select-option|value = <?php echo $option['file'];?>
	@templates-select-option = <?php echo ucfirst($option['title']);?>
	@templates-select-option|addNewAttribute = <?php if ($option['file'] == 'blank.html') echo 'selected';?>
	
@templates-select-option|after = <?php
	if ($optgroup != $option['folder']) {
		$optgroup = $option['folder'];
		echo "/<optgroup>";
	}
}}
?>

[data-vvveb-url]|data-vvveb-url = $this->saveUrl
form[data-vvveb-url]|action = $this->saveUrl
