<div class="content_columns width_435">
	<h3>Phone Numbers</h3>	
	<ul id="phone_numbers">
		<?php if ($phones): foreach ($phones as $phone): $phone_data = json_decode($phone->value) ?>	
		<li class="item_data" id="item_data_<?= $phone->user_meta_id ?>">
			<span class="actions action_<?= $phone_data->phone_type ?>"></span>
			<span class="item_data"> +1 <?= format_phone_number($phone_data->phone_number) ?></span>
			<ul class="item_actions">
				<li><a href="<?= base_url().'api/users/mobile_modify/id/'.$phone->user_meta_id ?>" class="mobile_edit_data"><span class="actions action_edit"></span> Edit</a></li>
				<li><a href="<?= base_url().'api/users/mobile_destroy/id/'.$phone->user_meta_id ?>" class="mobile_delete_data" rel="<?= $phone_data->phone_number ?>"><span class="actions action_delete"></span> Delete</a></li>
			</ul>
			<input type="hidden" id="data_<?= $phone->user_meta_id ?>" value='<?= $phone->value ?>'>
		</li>
	<?php endforeach; endif; ?>
	</ul>
	<p><input type="button" name="button_add_phone" id="button_add_phone" value="Add A Phone"></p>
</div>

<div class="content_columns width_435">
	<h3>Addresses</h3>
	<ul id="addresses">
	<?php if ($addresses): ?>
		<li class="item_data" id="address_1">
			<span class="actions action_address"></span>
			<span class="item_data"><?= character_limiter('My Rad Crib, 20 NW 16th Ave, Portland', 24) ?></span>
			<ul class="item_actions">
				<li><a href="<?= base_url().'api/users/mobile_modify/id/'; //$phone->user_meta_id ?>" class="edit_address"><span class="actions action_edit"></span> Edit</a></li>
				<li><a href="<?= base_url().'api/users/mobile_destroy/id/'; //$phone->user_meta_id ?>" class="delete_address" rel="<?php // $phone_data->phone_number ?>"><span class="actions action_delete"></span> Delete</a></li>
			</ul>
			<input type="hidden" id="phone_data_<?php // $addrress->user_meta_id ?>" value='<?php // $address->value ?>'>	
		</li>
	<?php endif; ?>
	</ul>
	<p><input type="button" name="button_add_address" id="button_add_address" value="Add An Address"></p>
</div>

<div class="clear"></div>
<script type="text/javascript">
$(document).ready(function()
{
	console.log(partials.item_data);

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
					doPlaceholder('#phone_number', '503-552-1212');
				},
				buttons:
				{
					'Add Mobile':function()
					{						
						if (isFieldValid('#phone_number', '503-552-1212', 'Enter a number') == true)
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

							 		$parent_dialog.dialog('close');
									$parent_dialog.remove();
							 	}
							});
						}				
					},
					'Cancel': function()
					{
						$parent_dialog.dialog('close');
						$parent_dialog.remove();						
					}					
				}
			});		
		});
	});
	
	// Edit Mobile
	$('.edit_mobile_phone').live('click', function(eve)
	{
		eve.preventDefault();
		var modify_url 		= $(this).attr('href');
		var user_meta_id	= modify_url.replace(base_url + 'api/users/mobile_modify/id/','');

		$.get(base_url + 'settings/mobile_phone_editor', function(partial_html)
		{
			var mobile_data = JSON.parse($('#phone_data_' + user_meta_id).val());
		
			console.log(mobile_data)
				
		
			$('<div />').html(partial_html).dialog(
			{
				width	: 350,
				modal	: true,
				title	: 'Edit Mobile Phone',
				create	: function()
				{
					$parent_dialog = $(this);

					$('[name=phone_number]').val(toPhoneFormat(mobile_data.phone_number));
					$("[name=phone_type] option[value=" + mobile_data.phone_type +"]").attr("selected","selected");

					if (mobile_data.phone_private == 'yes') $('[name=phone_private]').attr('checked', true);
					if (mobile_data.phone_active == 'yes') $('[name=phone_active]').attr('checked', true);
				},
				buttons:
				{
					'Save': function()
					{					
						var phone_data = $('#user_mobile_add').serializeArray();
						phone_data.push({'name':'module','value':'users'});		
					
						$(this).oauthAjax(
						{
							oauth 		: user_data,
							url			: modify_url,
							type		: 'POST',
							dataType	: 'json',
							data		: phone_data,
					  		success		: function(result)
					  		{	  			  			
								console.log(result);
								
							 	$parent_dialog.dialog('close');
								$parent_dialog.remove();								
						 	}
						});
					},
					'Cancel': function()
					{
						$parent_dialog.dialog('close');
						$parent_dialog.remove();						
					}
				}
			});
		});	
	});

	// Delete Mobile
	$('.delete_mobile_phone').live('click', function(eve)
	{
		eve.preventDefault();
		var delete_url		= $(this).attr('href');
		var user_meta_id	= delete_url.replace(base_url + 'api/users/mobile_destroy/id/','');
		var phone_number	= toPhoneFormat($(this).attr('rel'));
		var html 			= '<p>Are you want to delete ' + phone_number + '</p>';

		$('<div />').html(html).dialog(
		{
			width	: 350,
			modal	: true,
			title	: 'Delete Mobile Phone',
			create	: function() { $parent_dialog = $(this); },
			buttons:
			{
				'Yes':function()
				{
					$(this).oauthAjax(
					{
						oauth 		: user_data,
						url			: delete_url,
						type		: 'GET',
						dataType	: 'json',
				  		success		: function(result)
				  		{		
							$parent_dialog.dialog('close');
							$parent_dialog.remove();	
					 		$('#phone_' + user_meta_id).fadeOut();
					 	}
					});
				},
				'No':function()
				{
					$parent_dialog.dialog('close');
					$parent_dialog.remove();				
				}
			}
		});		
	});


	// Add Address
	$('#button_add_address').live('click', function(eve)
	{
		eve.preventDefault();
		
		
	});
	
	// Edit Address
	$('.edit_address').live('click', function(eve)
	{
		eve.preventDefault();
		var modify_url = $(this).attr('href');
		var user_meta_id	= modify_url.replace(base_url + 'api/users/address_modify/id/','');

		
	
	});


	// Delete Address
	$('.delete_address').live('click', function(eve)
	{
		eve.preventDefault();
		var delete_url		= $(this).attr('href');
		var user_meta_id	= delete_url.replace(base_url + 'api/users/address_destroy/id/','');
		var phone_number	= toPhoneFormat($(this).attr('rel'));
		var html 			= '<p>Are you want to delete ' + phone_number + '</p>';

		$('<div />').html(html).dialog(
		{
			width	: 350,
			modal	: true,
			title	: 'Delete Address Phone',
			create	: function() { $parent_dialog = $(this); },
			buttons:
			{
				'Yes':function()
				{
					$(this).oauthAjax(
					{
						oauth 		: user_data,
						url			: delete_url,
						type		: 'GET',
						dataType	: 'json',
				  		success		: function(result)
				  		{		
							$parent_dialog.dialog('close');
							$parent_dialog.remove();	
					 		$('#address_' + user_meta_id).fadeOut();
					 	}
					});
				},
				'No':function()
				{
					$parent_dialog.dialog('close');
					$parent_dialog.remove();				
				}
			}
		});		
	});	
	
	
});
</script>