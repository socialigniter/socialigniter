<form method="post" name="change_password" id="change_password" action="<?= base_url() ?>api/users/password">

	<h3>Change Password</h3>
	
	<p>Old Password</p>
	<input type="password" name="old_password" size="32" value="<?= set_value('old_password', $old_password) ?>"> <a href="<?= base_url() ?>forgot_password">Forgot password?</a>
	
	<p>New Password</p>
	<input type="password" name="new_password" size="32" value="<?= set_value('new_password', $new_password) ?>">

	<p>Confirm New</p>
	<input type="password" name="new_password_confirm" size="32" value="<?= set_value('new_password_confirm', $new_password_confirm) ?>">

	<p><input type="submit" value="Change" /></p>

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
				$('html, body').animate({scrollTop:0});
	  							
			 	$('[name=old_password]').val('');
			 	$('[name=new_password]').val('');
			 	$('[name=new_password_confirm]').val('');
	  				  					  			  			
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});
		 	}
		});		
	});	
});
</script>