import(common.tpl) 

[data-v-address-*] = <?php echo $this->address['@@__data-v-address-([a-zA-Z_\d]+)__@@'] ?? '';?>
