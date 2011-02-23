<h3>Password</h3>

<form method="post" name="change_password" id="change_password" action="<?= base_url() ?>api/users/password">
	<table border="0" cellpadding="0" cellspacing="0">
    <tr>
		<td valign="top">Current:</td>
		<td>
			<input type="password" name="old_password" size="32" value="<?= set_value('old_password', $old_password) ?>">
			<p><a href="<?= base_url() ?>forgot_password">Forgot your password?</a></p>
		</td>
	</tr>
    <tr>
		<td>New:</td>
		<td><input type="password" name="new_password" size="32" value="<?= set_value('new_password', $new_password) ?>"></td>
	</tr>
    <tr>	
		<td>Confirm:</td>
		<td><input type="password" name="new_password_confirm" size="32" value="<?= set_value('new_password_confirm', $new_password_confirm) ?>"></td>
	</tr>
    <tr>		
		<td colspan="2"><input type="submit" value="Change" /></td>
  	</tr>			
	</table>
</form>

<script type="text/javascript">
$(document).ready(function()
{
	// Write Article
	$("#change_password").bind("submit", function(e)
	{
		e.preventDefault();
	
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: $(this).attr('ACTION'),
			type		: 'POST',
			dataType	: 'json',
			data		: $('#change_password').serializeArray(),
	  		success		: function(result)
	  		{		  					  			  			
				if (result.status == 'success')
				{
				 	$('#content_message').html(result.message).addClass('message_alert').show('slow');
				 	$('[name=old_password]').val('');
				 	$('[name=new_password]').val('');
				 	$('[name=new_password_confirm]').val('');				 	
				 	$('#content_message').oneTime(4000, function(){$('#content_message').hide('normal')});
			 	}
			 	else
			 	{
				 	$('#content_message').html(result.message).addClass('message_alert').show('slow');
				 	$('#content_message').oneTime(4000, function(){$('#content_message').hide('normal')});			
			 	}	
		 	}
		});		
	});	
});
</script>