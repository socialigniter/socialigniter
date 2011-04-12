<?php if ($social_post = $this->social_igniter->get_social_post($this->session->userdata('user_id'), 'social_post')): ?>
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
	$('#content_publish, #content_save').bind('click', function(eve)
	{
		eve.preventDefault();
		$form = $('#<?= $form_name ?>');

		// Validation	
		if (validationRules(validation_rules))
		{
			// Strip Empty
			cleanAllFieldsEmpty(validation_rules);

			var status		= $(this).attr('name');		
			var form_data	= $form.serializeArray();
			form_data.push({'name':'module','value':'<?= $form_module ?>'},{'name':'type','value':'<?= $form_type ?>'},{'name':'source','value':'website'},{'name':'status','value':status});

			$form.oauthAjax(
			{
				oauth 		: user_data,
				url			: '<?= $form_url ?>',
				type		: 'POST',
				dataType	: 'json',
				data		: form_data,
		  		success		: function(result)
		  		{		  		
					$('html, body').animate({scrollTop:0});
					$('#content_message').notify({scroll:true,status:result.status,message:result.message});
					
					if (result.status == 'success')
					{					
						var new_status = displayContentStatus(result.data.status, result.data.approval);
						$('#content_status').html('<span class="actions action_' + new_status + '"></span> ' + new_status);	
					}
			 	}
			});
		}
		else
		{		
			eve.preventDefault();
		}	
	});
});
</script>