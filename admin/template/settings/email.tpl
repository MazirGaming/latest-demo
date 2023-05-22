import(common.tpl)

.settings|before = <?php
?>

.settings input[data-v-setting]|value = <?php 
	$_setting = '@@__data-v-setting__@@';
	echo $_POST['settings'][$_setting] ?? \Vvveb\arrayPath($this->email, $_setting) ?? '';
	//name="settings[setting-name] > get only setting-name
	//$_setting = '@@__name:\[(.*)\]__@@';
?>

.settings textarea[data-v-setting] = <?php 
	$_setting = '@@__data-v-setting__@@';
	echo $_POST['settings'][$_setting] ?? \Vvveb\arrayPath($this->email, $_setting) ?? '';
	//$_setting = '@@__name:\[(.*)\]__@@';
?>
