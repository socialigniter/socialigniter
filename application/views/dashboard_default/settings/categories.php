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
	
	$('.item_delete_category').bind('click', function(e)
	{
		e.preventDefault();
	

	
		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: base_url + 'api/categories/destroy/id/' + category_id,
			type		: 'GET',
			dataType	: 'json',
			data		: data,
		  	success		: function(result)
		  	{							  	
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});									
		  	}		
		});						
	});	
	
});
</script>