<?php if ($social_post = $this->social_igniter->get_social_post('<ul class="social_post">', '</ul>')): ?>
<h3>Share</h3>
<?= $social_post ?>
<?php endif; ?>

<h3>Status</h3>
<p id="content_status"><span class="actions action_<?= $status ?>"></span> <?= ucwords($status) ?></p>
		
<p><input type="submit" name="publish" id="content_publish" value="Publish" /> <input type="submit" name="save" id="content_save" value="Save" /></p>

<script type="text/javascript">
$(document).ready(function()
{
	// Publishes / Saves Content
	$('#content_publish, #content_save').bind('click', function()
	{	
		$(this).attr('disabled', 'disabled');
		$form = $('#classes_media');
		
		var status		= $(this).attr('name');			
		var form_data	= $form.serializeArray();
		form_data.push({'name':'source','value':'website'},{'name':'status','value':status});

		$form.oauthAjax(
		{
			oauth 		: user_data,
			url			: $form.attr('ACTION'),
			type		: 'POST',
			dataType	: 'json',
			data		: form_data,
	  		success		: function(result)
	  		{			  			  			
				if (result.status == 'success')
				{
					alert(status + ' performed successfully');
			 	}
			 	else
			 	{
				 	$('#content_message').html(result.message).addClass('message_alert').show('normal');
				 	$('#content_message').oneTime(3000, function(){$('#content_message').hide('fast')});			
			 	}	
		 	}
		});		
	});
});
</script>