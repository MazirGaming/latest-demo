import(listing.tpl, {"type":"product", "list": "products"})

input[name="module"]|value 	  = <?php echo htmlentities(Vvveb\get('module'));?>
input[name="action"]|value 	  = <?php echo htmlentities(Vvveb\get('action'));?>
input[name="type"]|value 	  = <?php echo htmlentities(Vvveb\get('type'));?>

import(filters.tpl)