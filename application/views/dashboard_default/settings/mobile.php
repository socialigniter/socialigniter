<?php if ($phones): ?>
<h3>Your Phone Numbers</h3>	
<ul style="width: 500px">
<?php foreach ($phones as $phone): $phone_data = json_decode($phone->value) ?>	
	<li class="item_data">
		<span class="item_data_title">+1 <?= $phone_data->phone_number ?></span>
		<span class="item_data_meta"><?= ucwords($phone_data->phone_type) ?></span>
		
		<ul class="item_actions">
			<li><a href="<?= base_url().'api/users/mobile_modify/id/'.$phone->user_meta_id ?>" class="edit_mobile_phone"><span class="actions action_edit"></span> Edit</a></li>
			<li><a href="<?= base_url().'api/users/mobile_delete/id/'.$phone->user_meta_id ?>" class="delete_mobile_phone" rel="<?= $phone_data->phone_number ?>"><span class="actions action_delete"></span> Delete</a></li>
		</ul>
	</li>
<?php endforeach; ?>
</ul>

<?php else: ?>
<p>Looks like you haven't added a mobile phone yet</p>
<?php endif; ?>

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
				},
				buttons:
				{
					'Add Mobile':function()
					{
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

							 		$parent_dialog.dialog('close');
									$parent_dialog.remove();
							 	}
							});
						});					
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

	// Edit Mobile
	$('.delete_mobile_phone').live('click', function(eve)
	{
		eve.preventDefault();
		var mobile_delete_url	= $(this).attr('href');
		var phone_number		= $(this).attr('rel');
		var html 				= '<p>Are you want to delete ' + phone_number + '</p>';

		$('<div />').html(html).dialog(
		{
			width	: 350,
			modal	: true,
			title	: 'Delete Mobile Phone',
			create	: function()
			{
				$parent_dialog = $(this);	
			
				$(this).oauthAjax(
				{
					oauth 		: user_data,
					url			: mobile_delete_url,
					type		: 'GET',
					dataType	: 'json',
			  		success		: function(result)
			  		{		  	
			  						  			  			
						console.log(result);

				 	}
				});						
			},
			buttons:
			{
				'Yes':function()
				{
					$parent_dialog.dialog('close');
					$parent_dialog.remove();				
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