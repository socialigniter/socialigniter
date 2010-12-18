<form method="post" id="status_update" action="<?= base_url() ?>status/add">
	<p><textarea id="status_update_text" name="content"></textarea></p>
	<div id="status_update_options">
		<?php if ($geo_locate): ?>
		<div id="status_update_geo">
			<a href="#" class="find_location" id="status_find_location"><span>Get Location</span></a>
		</div>
		<?php endif; ?>
		<?= $this->social_igniter->get_social_post('<ul class="social_post">', '</ul>'); ?>
		<div class="clear"></div>
	</div>
	<div id="status_update_post">
		<input type="submit" name="post" id="status_post" value="Share" />
		<span id="character_count"></span>
	</div>
	<input type="hidden" name="access" id="access" value="E" />
	<input type="hidden" name="geo_lat" id="geo_lat" value="" />
	<input type="hidden" name="geo_long" id="geo_long" value="" />
	<input type="hidden" name="geo_accuracy" id="geo_accuracy" value="" />
</form>
<script type="text/javascript">
// Placeholder
doPlaceholder('#status_update_text', "What's shaking?");

// Status
$("#status_update").bind("submit", function(eve)
{
	eve.preventDefault();

	var status_update		= $('#status_update_text').val();
	var valid_status_update = isFieldValid('#status_update_text', "What's shaking?", 'Please write something');

	// If isn't empty		
	if (valid_status_update == true)
	{		
		var status_data	= $('#status_update').serializeArray();
		status_data.push({'name':'module','value':'home'},{'name':'type','value':'status'},{'name':'source','value':'website'},{'name':'title','value':'d'});

		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/content/create',
			type		: 'POST',
			dataType	: 'json',
			data		: status_data,
		  	success		: function(result)
		  	{		
		  		console.log(result);
		  		  	
				if (result.status == 'success')
				{
				 	$('#feed').prepend(result.data).show('slow');
					$('#status_update_text').val('');						
					doPlaceholder('#status_update_text', "What's shaking?");
	                markNewItem($(html).attr('id'));				
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