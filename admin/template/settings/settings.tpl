import(common.tpl)

.settings|before = <?php
//KeyValue macro gets all attribute values, first parameter is selector second is attribute
$settings = @@macro KeyValue(".settings [data-v-setting]","data-v-setting","data-v-setting")@@;
$values = \Vvveb\get_setting($settings);
?>

.settings input[data-v-setting]|value = <?php 
	$_setting = '@@__data-v-setting__@@';
	echo $_POST['settings'][$_setting] ?? $values[$_setting] ?? '';
	//name="settings[setting-name] > get only setting-name
	//$_setting = '@@__name:\[(.*)\]__@@';
?>

.settings textarea[data-v-setting] = <?php 
	$_setting = '@@__data-v-setting__@@';
	echo $_POST['settings'][$_setting] ?? Vvveb\get_setting($_setting, '');
	//$_setting = '@@__name:\[(.*)\]__@@';
?>