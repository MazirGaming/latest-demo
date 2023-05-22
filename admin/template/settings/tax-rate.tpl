import(crud.tpl, {"type":"tax_rate"})

@zone_group = [data-v-zone-groups] [data-v-zone-group]
@zone_group|deleteAllButFirstChild

@zone_group|before = <?php
$count = 0;
if(isset($this->zone_groups) && is_array($this->zone_groups)) {
	foreach ($this->zone_groups as $zone_group_index => $zone_group) {?>
	
	[data-v-zone-group-*]|innerText  = $zone_group['@@__data-v-zone-group-(*)__@@']
	option[data-v-zone-group-*]|value = $zone_group['@@__data-v-zone-group-(*)__@@']	
	
	@zone_group|after = <?php 
		$count++;
	} 
}?>
