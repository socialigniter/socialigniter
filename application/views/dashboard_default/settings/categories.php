<ol id="feed">
	<?= $categories_view ?>
</ol>
<div class="clear"></div>
<script type="text/javascript">
$(document).ready(function()
{	
	// Add Category
/*
	$('.item_edit_category').bind('click', function(e)
	{
		e.preventDefault();
		var category_id = $(this).attr('id').replace('item_action_edit_', '');

		$.categoryManager(
		{
			action		: 'edit',	
			module		: jQuery.url.segment(1),
			type		: 'class-category',
			title		: 'Edit ' + jQuery.url.segment(1) + ' Category',
			data		: category_id
		});			
	});
*/

	// Add Category
	$('.item_delete_category').bind('click', function(e)
	{
		e.preventDefault();
		alert('will delete category and strip content in this category')		
	});
});
</script>