<form method="post" id="status_update" action="<?= base_url() ?>api/content/create">
	<textarea id="status_update_text" name="content"></textarea>
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
// Placeholder
doPlaceholder('#status_update_text', "<?= $home_greeting ?>");

// Do Geo
geo_get();

// Status
$("#status_update").bind("submit", function(eve)
{
	eve.preventDefault();

	var status_update		= $('#status_update_text').val();
	var valid_status_update = isFieldValid('#status_update_text', "<?= $home_greeting ?>", 'Please write something');

	// Valid		
	if (valid_status_update == true)
	{		
		var status_data	= $('#status_update').serializeArray();
		status_data.push({'name':'module','value':'home'},{'name':'type','value':'status'},{'name':'source','value':'website'},{'name':'comments_allow','value':'Y'});

		$(this).oauthAjax(
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
					$.get(base_url + 'home/item_timeline',function(html)
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
					doPlaceholder('#status_update_text', "What's shaking?");
			 	}
			 	else
			 	{
					$('#content_message').notify({message:result.message});				
			 	}	
		 	}
		});
	}
});
</script>