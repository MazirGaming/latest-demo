import(crud.tpl, {"type":"zone"})

@country = [data-v-countries] [data-v-country]
@country|deleteAllButFirstChild

@country|before = <?php
$count = 0;
$country_index = 0;
if(isset($this->countries) && is_array($this->countries)) {
	foreach ($this->countries as $country_index => $country) {?>
	
	[data-v-country-*]|innerText  = $country['@@__data-v-country-(*)__@@']
	option[data-v-country-*]|value = $country['@@__data-v-country-(*)__@@']	
	@country|addNewAttribute = <?php if (isset($this->zone['country_id']) && ($country['country_id'] == $this->zone['country_id'])) echo 'selected';?>
	
	@country|after = <?php 
		$count++;
	} 
}?>
