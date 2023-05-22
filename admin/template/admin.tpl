[data-v-url]|href = <?php echo htmlentities(Vvveb\url('@@__data-v-url__@@'));?>
form[data-v-url]|action = <?php echo htmlentities(Vvveb\url('@@__data-v-url__@@'));?>

[data-v-url-params]|href = <?php echo Vvveb\url(@@__data-v-url-params__@@);?>
form[data-v-url-params]|action = <?php echo Vvveb\url(@@__data-v-url-params__@@);?>

[data-v-url][data-v-url-params]|href = <?php echo Vvveb\url('@@__data-v-url__@@' , @@__data-v-url-params__@@);?>
form[data-v-url][data-v-url-params]|action = <?php echo Vvveb\url('@@__data-v-url__@@' , @@__data-v-url-params__@@);?>


				  
/*body|prepend = <?php var_dump($this);?>*/

head base|href = <?php echo Vvveb\themeUrlPath()?>

[data-v-admin-live-url]|href = <?php echo $this->live_url;?>
//csrf
[data-v-csrf]|value = <?php echo \Vvveb\session('csrf');?>


[data-v-validator-json] = <?php if (isset($this->validator_json)) echo 'validator_json = ' . $this->validator_json;?>

@@_CONSTANT_PUBLIC_PATH_@@ = <?php echo PUBLIC_PATH;?>

