import(common.tpl)

.adminer iframe|src = <?php echo $_SERVER['REQUEST_URI'] . '&action=iframe';?>
