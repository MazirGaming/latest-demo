@page = [data-pagination] [data-page]
@page|deleteAllButFirst

[data-pagination]|before = <?php $max_pages = 5; $visible_pages = 3; 

$parent_component = '@@__data-v-parent-component__@@';
$parent_index = '@@__data-v-parent-index__@@';
if ($parent_component) {
	$component = $this->_component[$parent_component][$parent_index];	
}

if(isset($component['count'])) {
	
if (isset($component['limit'])) $limit = $component['limit']; else $limit = 10;
	
$page_count = ceil($component['count'] / $limit);

$page = 1;
$page_stop = $page_count;
$url = '@@__data-v-url__@@';

if (empty($url)) {
	$url        = Vvveb\System\Core\FrontController :: getRoute();
	$parameters = Vvveb\System\Core\Request :: getInstance()->get;
}

if (isset($_GET['page'])) {
	$current_page = $_GET['page']; 
} else  if (isset($this->pagenum)) {
	$current_page = $this->pagenum; 
} else {
	$current_page = 1;
}

$current_page = max($current_page, 1);

if ($page_count > $max_pages)
{
	if ($current_page > $visible_pages)
	{
		if (($current_page + $visible_pages) > $page_count)
		{
			$page = $page_count - $max_pages - 1;
			$page_stop = $page_count;
		} else 
		{
			$page = $current_page - $visible_pages;
			$page_stop = $current_page + $visible_pages;
		}
	} else
	{
		$page = 1;
		$page_stop = $max_pages;
	}
}
?>

@page|before = <?php  
	for (;$page <= $page_stop;$page++) {
?>

	[data-pagination] [data-pages] = $page_count
	
	@page [data-page-no] = $page
	@page [data-page-url]|href = <?php echo htmlentities(Vvveb\url($url, ['page' => $page] + $parameters));?>
	@page|addClass = <?php if ($current_page == $page) echo 'active'?>

@page|after = <?php 
	} 
?>

	[data-pagination] [data-count] = $component['count']
	[data-pagination] [data-current-page] = $current_page
	[data-pagination] [data-current-url]|action = <?php echo htmlentities(Vvveb\url($url, ['page' => $current_page]  + $parameters));?>
	
	[data-pagination] [data-first] [data-page-url]|href = <?php echo htmlentities(Vvveb\url($url, ['page' => 1]  + $parameters));?>
	[data-pagination] [data-prev]  [data-page-url]|href = <?php echo htmlentities(Vvveb\url($url, ['page' => max($current_page - 1, 1)]  + $parameters));?>
	[data-pagination] [data-next]  [data-page-url]|href = <?php echo htmlentities(Vvveb\url($url, ['page' => min($current_page + 1, $page_count)]  + $parameters));?>
	[data-pagination] [data-last]  [data-page-url]|href = <?php echo htmlentities(Vvveb\url($url, ['page' => $page_count]  + $parameters));?>


[data-pagination]|after = <?php 
	} 
?>
