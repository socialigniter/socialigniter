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
	$('#content_publish, #content_save').live('click', function(eve)
	{
		eve.preventDefault();
		$(this).attr('disabled', 'disabled');
		$form = $('#<?= $form_name ?>');

		// Validation Stuffs
		var check_count = 0;
		var valid_count = 0;
		var this_valid	= false;
		
		$.each(form_validation, function(key, item)
		{			 
			if (item.message != '')
			{				
				check_count++;
							
				if (isFieldValid(item.element, item.holder, item.message) == true)
				{
					valid_count++;
				}	
			}
		});
		
		// Validation	
		if (check_count == valid_count)
		{
			console.log('shizzle is valid');
		
			// Strip Empty Fields
			cleanFieldEmpty('#tags', "Gardening, Fruit, Vegetables");		
		
			var status		= $(this).attr('name');			
			var form_data	= $form.serializeArray();
					
			form_data.push({'name':'module','value':'<?= $form_module ?>'},{'name':'source','value':'website'},{'name':'status','value':status});
	
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
			 	}
			});	
		}
		else
		{
			console.log('shizzle be not valid');
		
			eve.preventDefault();
		}	
	});
});
</script>