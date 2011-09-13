<ol id="feed">
	<?= $categories_view ?>
</ol>
<div class="clear"></div>
<script type="text/javascript">
$(document).ready(function()
{	
	// Delete Category
	$('.item_delete_category').bind('click', function(e)
	{
		e.preventDefault();
		alert('will delete category and strip content in this category')		
	});
});
</script>