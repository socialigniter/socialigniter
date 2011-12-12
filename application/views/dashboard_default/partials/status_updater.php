<form method="post" id="status_update" action="<?= base_url() ?>api/content/create">
	<textarea id="status_update_text" placeholder="<?= $home_greeting ?>" name="content"></textarea>	
	<div id="status_update_options">
		<?php if ($logged_geo_enabled): ?>
		<div id="status_update_geo">
			<a href="#" class="find_location" id="status_find_location"><span>Get Location</span></a>
		</div>
		<?php endif; ?>
		<?= $social_post ?>
		<div class="clear"></div>
	</div>
	<div id="status_update_post">
		<input type="submit" name="post" id="status_post" value="Share" />
		<span id="character_count"></span>
	</div>
	<input type="hidden" name="access" id="access" value="E" />
	<input type="hidden" name="geo_lat" id="geo_lat" value="" />
	<input type="hidden" name="geo_long" id="geo_long" value="" />
</form>

<script type="text/javascript">
$(document).ready(function()
{
	// Geo
	geo_get();

	// Character Counter
	$('#status_update_text').NobleCount('#character_count',
	{
		on_negative: 'color_red'
	});	

	// Update Status
	$('#status_update').bind('submit', function(e)
	{
		e.preventDefault();
		$.validator(
		{
			elements : 
				[{
					'selector' 	: '#status_update_text',
					'rule'		: 'require', 
					'field'		: 'Please write something...',
					'action'	: 'element'
				}],
			message	 : '',
			success	 : function()
			{		
				var status_data	= $('#status_update').serializeArray();
				status_data.push({'name':'category_id','value':$('#select_group').val()},{'name':'module','value':'home'},{'name':'type','value':'status'},{'name':'source','value':'website'},{'name':'comments_allow','value':'Y'});
		
				$.oauthAjax(
				{
					oauth 		: user_data,
					url			: base_url + 'api/content/create',
					type		: 'POST',
					dataType	: 'json',
					data		: status_data,
				  	success		: function(result)
				  	{		  		  	
						if (result.status == 'success')
						{
							// Social Post
							var social_post = $('input.social_post');
							if (social_post.length > 0)
							{
								$.each(social_post, function()
								{
									var social_api = $(this).attr('name');
									if ($('#social_post_' + social_api).is(':checked'))
									{							
										$.oauthAjax(
										{
											oauth 		: user_data,
											url			: base_url + 'api/' + social_api + '/social_post',
											type		: 'POST',
											dataType	: 'json',
											data		: status_data,
										  	success		: function(social_result)
										  	{
										  		// Need to do some notification
												// console.log(social_result);							
											}
										});
									}
								});
							}				
															
							$.get(base_url + 'home/item_timeline', function(html)
							{
								var newHTML = $.template(html,
								{
									'ITEM_ID'			 :result.activity.activity_id,
									'ITEM_AVATAR'		 :getUserImageSrc(result.data),
									'ITEM_COMMENT_AVATAR':getUserImageSrc(result.data),
									'ITEM_PROFILE'		 :result.data.username,
									'ITEM_CONTRIBUTOR'	 :result.data.name,
									'ITEM_CONTENT'		 :result.data.content,
									'ACTIVITY_TYPE'		 :result.activity.type,
									'ITEM_DATE'			 :'just now',
									'ACTIVITY_MODULE'	 :result.activity.module,
									'ITEM_CONTENT_ID'	 :result.data.content_id
								});
								
								$('#feed').prepend(newHTML);
							});

							$('#status_update_text').val('');
					 	}
					 	else
					 	{
							$('#content_message').notify({message:result.message});				
					 	}	
				 	}
				});
			}
		});
	});

	// Add Category
	$('#select_group').categoryManager(
	{
		action		: 'create',		
		module		: 'home',
		type		: 'group',
		title		: 'Add Group'
	});
	
	// Browse Group
	$('#select_group').change(function()
	{
		var group_id = $(this).val();
		if (group_id != 'add_category')
		{
    		window.location = base_url + 'home/group/' + $(this).val();
    	}
    });


});
</script>