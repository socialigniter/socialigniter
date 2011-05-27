<ol id="feed">
	<?= $categories_view ?>
</ol>
<div class="clear"></div>

<script type="text/javascript">
$(document).ready(function()
{	
	// Add Category
	$('.item_edit_category').bind('click', function(eve)
	{
		eve.preventDefault();

		$.categoryManager(
		{
			url_api		: base_url + 'api/categories/view/module/<?= $this->uri->segment(2) ?>',
			url_pre		: base_url + '<?= $this->uri->segment(2) ?>/',
			url_sub		: base_url + 'api/categories/create',		
			module		: 'classes',
			type		: 'class-category',
			title		: 'Edit <?= ucwords($this->uri->segment(2)) ?> Category',
			slug_value	: '',
			details		: '{"thumb":"","locations":{}}',
			trigger		: $('.content [name=category_id]')
		});			
	});

	// Add Category
	$('.item_delete_category').bind('click', function(eve)
	{
		eve.preventDefault();

		alert('will delete category and strip content in this category')		
	});
	
});
</script>