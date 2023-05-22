@user = [data-v-component-user]

@user|prepend = <?php 
	if (isset($_user_idx)) $_user_idx++; else $_user_idx = 0;
	$previous_component = isset($component)?$component:null;
	$user = $component = $this->_component['user'][$_user_idx];
	//$user = \Vvveb\session('user');
?>

@user [data-v-user-*]|innerText = $user['@@__data-v-user-(*)__@@']

@user|append = <?php 
	$component = $previous_component;
?>