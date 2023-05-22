@addresscomp = [data-v-component-address]
@address  = [data-v-component-address] [data-v-address]

@address|deleteAllButFirstChild

@addresscomp|prepend = <?php
if (isset($_addresscomp_idx)) $_addresscomp_idx++; else $_addresscomp_idx = 0;
$previous_component = isset($current_component)?$current_component:null;
$addresscomp = $current_component = $this->_component['address'][$_addresscomp_idx] ?? [];

$count = $_pagination_count = $addresscomp['count'] ?? 0;
$_pagination_limit = isset($addresscomp['limit']) ? $addresscomp['limit'] : 5;	
$addresses = $addresscomp['address'] ?? [];
?>


@address|before = <?php
if($addresses) {
	foreach ($addresses as $index => $address) {?>
		
		@address|data-address_id = $address['address_id']
		
		@address|id = <?php echo 'address-' . $address['address_id'];?>
		
		@address [data-v-address-label-id]|id = <?php echo 'address_' . $address['address_id'];?>
		@address [data-v-address-label-for]|for = <?php echo 'address_' . $address['address_id'];?>
		
		@address img[data-v-address-*]|src = $address['@@__data-v-address-([a-zA-Z_\d]+)__@@']
		
		@address [data-v-address-*]|innerText = $address['@@__data-v-address-([a-zA-Z_\d]+)__@@']
		
		@address input[data-v-address-*]|value = $address['@@__data-v-address-([a-zA-Z_\d]+)__@@']
		
		@address a[data-v-address-*]|href = $address['@@__data-v-address-([a-zA-Z_\d]+)__@@']
	
	@address|after = <?php 
	} 
}
?>