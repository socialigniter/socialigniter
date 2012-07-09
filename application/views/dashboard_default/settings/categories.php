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
		var category_id = $(this).data('category_id');
		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: base_url + 'api/categories/destroy/id/' + category_id,
			type		: 'GET',
			dataType	: 'json',
		  	success		: function(result)
		  	{							  	
				$('html, body').animate({scrollTop:0});
				$('#item_' + category_id).fadeOut();				
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});									
		  	}		
		});	
	});	
	
});
</script>