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
	<?php if ($state == 'form'): ?>
	// Publishes / Saves Content from form
	$('#content_publish, #content_save').bind('click', function(e)
	{
		e.preventDefault();
		var status = $(this).attr('name');

		// Validation
		$.validator(
		{
			elements : validation_rules,
			message	 : '',
			success	 : function()
			{
				var form_data	= $('#content_editor_form').serializeArray();
				form_data.push({'name':'module','value':'<?= $form_module ?>'},{'name':'type','value':'<?= $form_type ?>'},{'name':'source','value':'website'},{'name':'status','value':status});
	
				$.oauthAjax(
				{
					oauth 		: user_data,
					url			: $.data(document.body, 'api_url'),
					type		: 'POST',
					dataType	: 'json',
					data		: form_data,
			  		success		: function(result)
			  		{		  				  		
						$('html, body').animate({scrollTop:0});
						$('#content_message').notify({status:result.status,message:result.message});
						
						if (result.status == 'success')
						{					
							var new_status = displayContentStatus(result.data.status, result.data.approval);
							$('#content_status').html('<span class="actions action_' + new_status + '"></span> ' + new_status);	
						
							$.updateContentManager( 
							{
								page_url		: base_url + 'home/' + current_module + '/manage/' + result.data.content_id,
								api_url			: base_url + 'api/content/modify/id/' + result.data.content_id,
								link_elements	: 'div.create_stage a', 
								link_url		: result.data.content_id
						  	});
						}
				 	}
				});
			}
		});
	});			
	<?php elseif ($state == 'button'): ?>
	// Publish / Saves Simple Button
	$('#content_publish, #content_save').bind('click', function(eve)
	{
		eve.preventDefault();
		var type = $(this).attr('name');

		$.oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/content/' + type + '/id/<?= $content_id ?>',
			type		: 'GET',
			dataType	: 'json',
	  		success		: function(result)
	  		{		  		
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({status:result.status,message:result.message});
				
				if (result.status == 'success')
				{					
					var new_status = displayContentStatus(result.data.status, result.data.approval);
					$('#content_status').html('<span class="actions action_' + new_status + '"></span> ' + new_status);	
				}
		 	}
		});
	});		
	<?php else: endif; ?>
});
</script>