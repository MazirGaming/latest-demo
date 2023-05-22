import(common.tpl)

/* input elements */
[data-v-{{type}}] input[data-v-{{type}}-*]|value = 
<?php
	 $name = '@@__data-v-{{type}}-(*)__@@';
	 if (isset($_POST['{{type}}'][$name])) 
		echo $_POST['{{type}}'][$name]; 
	 else if (isset($this->{{type}}[$name])) 
		echo $this->{{type}}[$name];
	 else echo '@@__value__@@';
?>


/* textarea elements */
[data-v-{{type}}] textarea[data-v-{{type}}-*] = 
<?php
	 $name = '@@__data-v-{{type}}-(*)__@@';
	 if (isset($_POST['{{type}}'][$name])) 
		echo $_POST['{{type}}'][$name]; 
	 else if (isset($this->{{type}}[$name])) 
		echo $this->{{type}}[$name];
	 else echo '@@__innerHTML__@@';
?>

[data-v-{{type}}] select[data-v-{{type}}-*]|before = 
<?php
	 $name = '@@__data-v-{{type}}-(*)__@@';
	 $selected = '';	
	 if (isset($this->{{type}}[$name])) 
	 $selected = $this->{{type}}[$name];
?>

[data-v-{{type}}] [data-v-{{type}}-*] [data-v-option]|deleteAllButFirstChild
[data-v-{{type}}] [data-v-{{type}}-*] [data-v-option]|before = <?php 
	foreach ($this->$name as $value => $text) {
	?>

	[data-v-{{type}}] [data-v-{{type}}-*] [data-v-option]|value = $value
	[data-v-{{type}}] [data-v-{{type}}-*] [data-v-option]|addNewAttribute = <?php if ($value == $selected) echo 'selected';?>
	[data-v-{{type}}] [data-v-{{type}}-*] [data-v-option] = $text

[data-v-{{type}}] [data-v-{{type}}-*] [data-v-option]|after = <?php 
} ?>

[data-v-{{type}}] [data-v-image]|data-v-image = $this->{{type}}['image_url']
[data-v-{{type}}] input[data-v-image]|value = $this->{{type}}['image']
[data-v-{{type}}] img[data-v-image]|src = <?php echo (isset($this->{{type}}['image_url']) && $this->{{type}}['image_url']) ? $this->{{type}}['image_url'] : 'img/placeholder.svg';?>
