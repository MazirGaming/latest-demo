import(listing.tpl, {"type":"post", "list": "posts"})

[data-v-type_name_plural] = $this->type_name_plural
[data-v-type-name] 		  = $this->type_name
[data-v-type] 			  = $this->type
a[data-v-addurl]|href 	  = $this->addUrl

input[name="module"]|value 	  = <?php echo htmlentities(Vvveb\get('module'));?>
input[name="action"]|value 	  = <?php echo htmlentities(Vvveb\get('action'));?>
input[name="type"]|value 	  = <?php echo htmlentities(Vvveb\get('type'));?>

import(filters.tpl)