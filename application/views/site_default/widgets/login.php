<?php if ((config_item('users_login') == 'TRUE') && (!$this->social_auth->logged_in())) { ?>
<h2>Login</h2>
<form method="post" id="login_widget" name="login_widget" action="<?= base_url() ?>login">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td>Email:</td>
	<td><input type="text" name="email" value=""></td>
</tr>
<tr>
	<td colspan="2"><div class="asdasd" id="email_error"></div></td>
</tr>
<tr>
	<td>Password:</td>
	<td><input type="password" name="password" value=""></td>
</tr>
<tr>
	<td>Remember:</td>
	<td><?= form_checkbox('remember', '1', TRUE, 'id="login_remember"');?> 
	<a href="<?= base_url()."forgot_password"; ?>">Forgot password?</a>
	</td>
</tr>
<tr>
	<td colspan="2"><input type="submit" name="submit" value="Login"></td>
</tr>
<tr>
	<td colspan="2"><?= $this->social_igniter->get_social_logins('<div class="social_login">', '</div>'); ?></td>
</tr>
</table>
</form>
	
<script type="text/javascript">
// Elements for Placeholder
var validation_rules = [{
	'element' 	: '[name=email]', 
	'holder'	: 'your@email.com', 
	'message'	: 'Enter your email'	
},{
	'element' 	: '[name=password]', 
	'holder'	: 'password', 
	'message'	: 'password123'	
}]

$(document).ready(function()
{
	// Placeholders
	makePlaceholders(validation_rules);

	$("#login_widget").bind('submit', function(eve)
	{	
		eve.preventDefault();

		var email_valid	= validateEmailAddress($('[name=email]').val());		
		
		// Validation	
		if (validationRules(validation_rules) && email_valid == true)
		{
			var login_data = $('#login_widget').serializeArray();		
		
			$.ajax(
			{
				url			: base_url + 'api/users/login',
				type		: 'POST',
				dataType	: 'json',
				data		: login_data,
		  		success		: function(result)
		  		{
					$('html, body').animate({scrollTop:0});
					
					if (result.status == 'success')
					{
						setTimeout(function() { window.location.href = base_url + 'home' });					
					}
					else
					{
						$('#content_message').notify({status:result.status,message:result.message});					
					}
			 	}
			});
		}
		else if (validationRules(validation_rules) && email_valid == false)
		{		
			$('#email_error').html('That email address is invalid').show('slow');
			$('#email_error').delay(2500).hide('slow');		
		}
		else
		{
			eve.preventDefault();
		}
	});
});
</script>
<?php } ?>