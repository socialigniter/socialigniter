<?php if ($phones): foreach ($phones as $phone): $details = json_decode($phone->details) ?>
		
	<h3>Your Phone</h3>	
	<p>You may send status updates from this phone number:</p>
	<h3>+1 <?= $phone ?></h3>

	<div class="content_norm_separator"></div>		
	
	<h3>Mobile Settings</h3>
	<form method="post" name="user_mobile_modify" id="user_mobile_modify" action="<?= base_url() ?>api/users/mobile_modify/id/<?= $phone->user_meta_id ?>">
		<table border="0" cellpadding="0" cellspacing="0">
	    <tr>		
			<td>Phone:</td>
			<td>+1 <?= $phone ?><input type="hidden" name="value" value="<?= $phone ?>" /></td>
		</tr>
		<tr>
			<td>Type:</td>
			<td><?= form_dropdown('type', config_item('user_mobile_types'), $details->type); ?></td>
		</tr>		
		<tr>
			<td>Search:</td>
			<td><?= form_checkbox($details->search); ?> Allow others to find you by phone number</td>
		</tr>
		<tr>
			<td>Text Updates:</td>
			<td><?= form_dropdown('phone_active', $phone_active_array, $details->active); ?></td>
		</tr>
		<tr>
			<td><a href="<?= base_url() ?>api/users/mobile_delete">Delete number</a></td>
		</tr>	
		</table>
	</form>
<?php endforeach; endif; ?>

<p>Looks like you haven't added a mobile phone yet</p>

<p><input type="button" name="button_add_phone" id="button_add_phone" value="Add A Phone"></p>

<script type="text/javascript">
$(document).ready(function()
{
	// Add Mobile
	$("#button_add_phone").live('click', function(eve)
	{
		eve.preventDefault();
		$.get(base_url + 'settings/mobile_phone_editor', function(partial_html)
		{	
			$('<div />').html(partial_html).dialog(
			{
				width	: 350,
				modal	: true,
				title	: 'Add Mobile Phone',
				create	: function()
				{
					$parent_dialog = $(this);
					
					$('#mobile_phone_editor').bind('submit', function(eve)
					{
						eve.preventDefault();
						var phone_data = $('#mobile_phone_editor').serializeArray();
						phone_data.push({'name':'module','value':'users'});		
					
						$(this).oauthAjax(
						{
							oauth 		: user_data,
							url			: base_url + 'api/users/mobile_add',
							type		: 'POST',
							dataType	: 'json',
							data		: phone_data,
					  		success		: function(result)
					  		{
								console.log(result);
						 	}
						});	
						
					});
				}
			});		
	
		});
	});
	
	// Edit Mobile
	$('.edit_mobile_phone').live('click', function(eve)
	{
		eve.preventDefault();
		$.get(base_url + 'settings/mobile_phone_editor', function(partial_html)
		{
			$('<div />').html(partial_html).dialog(
			{
				width	: 350,
				modal	: true,
				title	: 'Edit Mobile Phone',
				create	: function()
				{
					$parent_dialog = $(this);
					
					var phone_data = $('#user_mobile_add').serializeArray();
					phone_data.push({'name':'module','value':'users'});		
				
					$(this).oauthAjax(
					{
						oauth 		: user_data,
						url			: base_url + 'api/users/mobile_modify/id/' + user_meta_id,
						type		: 'POST',
						dataType	: 'json',
						data		: phone_data,
				  		success		: function(result)
				  		{		  					  			  			
							console.log(result);

					 	}
					});						
					
				}
		
			});		
		
		});
	
	});
	
});
</script>