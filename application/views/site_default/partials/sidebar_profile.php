<?php if ($user_id != $logged_user_id): ?>
	<h3>Connect</h3>
	<p><img src="<?= $dashboard_assets ?>icons/users_24.png"><input type="button" id="follow_user" class="button_basic_on" value="Follow"></p>
	<p><img src="<?= $dashboard_assets ?>icons/messages_24.png"><input type="button" id="message_user" class="button_basic_on" href="<?= $message_url ?>" value="Message"></p>
<?php else: ?>
	<h3><?= random_element(config_item('cool_salutations')) ?>,</h3>
	<p>This is your <?= random_element(config_item('cool_phrases')) ?> profile</p>
<?php endif; ?>
	<div class="profile_sidebar_separator"></div>

<?php if ($connections): ?>

	<h3>Connections</h3>
	<ul>
	<?php foreach ($connections as $connection): ?>
		<li><a class="profile_sidebar_icon" href="<?= $connection->url.$connection->connection_username ?>" target="_blank"> <img src="<?= base_url().'application/modules/'.$connection->module.'/assets/'.$connection->module ?>_24.png"> <?= $connection->connection_username ?></a></li>
	<?php endforeach; ?>
	</ul>
	
	<div class="profile_sidebar_separator"></div>

<?php endif; ?>

	<h3>People</h3>
	<p>Thumbnails of friends</p>
	

<script type="text/javascript">
$(document).ready(function()
{
	$("#follow_user").bind('click', (function(eve)
	{
		eve.preventDefault();

		// Validation	
		var follow_data = $('#follow_user').serializeArray();
		follow_data.push({'name':'module','value':'site'});

		console.log(follow_data);

		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: '<?= $follow_url ?>',
			type		: 'POST',
			dataType	: 'json',
			data		: follow_data,
	  		success		: function(result)
	  		{	
	  			console.log(result);
	  				  			  			
				if (result.status == 'success')
				{
//			 		$('#content_message').notify({message: result.message + ' <a href="' + base_url + 'pages/view/' + result.data.content_id + '">' + result.data.title + '</a>'});
					alert('yay good shiz');
			 	}
			 	else
			 	{
//			 		$('#content_message').notify({message: result.message + ' <a href="' + base_url + 'pages/view/' + result.data.content_id + '">' + result.data.title + '</a>'});
					alert('oops poopy shiz');
			 	}	
		 	}
		});
		
	});	
	
});
</script>