import(crud.tpl, {"type":"zone_group"})

@zone = [data-v-zones] [data-v-zone]
@zone|deleteAllButFirstChild

@zone|before = <?php
$count = 0;
$zone_index = 0;
$zone = [];
if(isset($this->zones) && is_array($this->zones)) {
	foreach ($this->zones as $zone_index => $zone) { ?>
	
	@zone [data-v-zone-*]|name  = <?php echo "zone[$zone_index][@@__data-v-zone-(*)__@@]";?>
	@zone [data-v-zone-*]|data-v-zone-id  = <?php echo $zone['zone_id'];?>

	@zone [data-v-zone-*]|innerText  = $zone['@@__data-v-zone-(*)__@@']
	@zone input[data-v-zone-*]|value = $zone['@@__data-v-zone-(*)__@@']	
	@zone a[data-v-zone-*]|href 	 = $zone['@@__data-v-zone-(*)__@@']	
	
	@zone|after = <?php 
		$count++;
	} 
}?>


@country = [data-v-countries] [data-v-country]
@country|deleteAllButFirstChild

@country|before = <?php
$count = 0;
$country_index = 0;
if(isset($this->countries) && is_array($this->countries)) {
	foreach ($this->countries as $country_index => $country) {?>
	
	[data-v-country-*]|innerText  = $country['@@__data-v-country-(*)__@@']
	option[data-v-country-*]|value = $country['@@__data-v-country-(*)__@@']	
	@country|addNewAttribute = <?php if (isset($zone['country_id']) && ($country['country_id'] == $zone['country_id'])) echo 'selected';?>
	
	@country|after = <?php 
		$count++;
	} 
}?>
