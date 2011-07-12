<div class="content_columns width_435">
	<?php if ($phones): ?>
	<h3>Phone Numbers</h3>	
	<ul id="phone_numbers">
	<?php foreach ($phones as $phone): $phone_data = json_decode($phone->value) ?>	
		<li class="item_data" id="phone_<?= $phone->user_meta_id ?>">
			<span class="actions action_<?= $phone_data->phone_type ?>"></span> +1 <?= format_phone_number($phone_data->phone_number) ?>
			<ul class="item_actions">
				<li><a href="<?= base_url().'api/users/mobile_modify/id/'.$phone->user_meta_id ?>" class="edit_mobile_phone"><span class="actions action_edit"></span> Edit</a></li>
				<li><a href="<?= base_url().'api/users/mobile_destroy/id/'.$phone->user_meta_id ?>" class="delete_mobile_phone" rel="<?= $phone_data->phone_number ?>"><span class="actions action_delete"></span> Delete</a></li>
			</ul>
		</li>
	<?php endforeach; ?>
	</ul>
	
	<?php else: ?>
	<p>No Phone Numbers Added</p>
	<?php endif; ?>
	<p><input type="button" name="button_add_phone" id="button_add_phone" value="Add A Phone"></p>
</div>

<div class="content_columns width_435">
	<h3>Addresses</h3>
	<ul id="addresses">
		<li class="item_data" id="address_1">
			<span class="actions action_address"></span>
			<span class="item_data_title">My Rad Crib, 20 NW 16th Ave...</span>
			<ul class="item_actions">
				<li><a href="<?= base_url().'api/users/mobile_modify/id/'.$phone->user_meta_id ?>" class="edit_mobile_phone"><span class="actions action_edit"></span> Edit</a></li>
				<li><a href="<?= base_url().'api/users/mobile_destroy/id/'.$phone->user_meta_id ?>" class="delete_mobile_phone" rel="<?= $phone_data->phone_number ?>"><span class="actions action_delete"></span> Delete</a></li>
			</ul>
		</li>
		<li class="item_data">
			<span class="actions action_address"></span>
			<span class="">This will be implemented with tie-in to the Places</span>
		
		</li>
	</ul>
	<p><input type="button" name="button_add_address" id="button_add_address" value="Add An Address"></p>
</div>

<div class="clear"></div>
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
		var mobile_modify_url = $(this).attr('href');
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
					doPlaceholder('#phone_number', '503-552-1212');
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
							url			: mobile_modify_url,
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
		
	});
});
</script>