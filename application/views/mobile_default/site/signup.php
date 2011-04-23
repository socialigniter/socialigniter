<h1>Signup</h1>

<?php if (config_item('users_signup') == 'TRUE'): ?>
<form method="post" name="user_signup" id="user_signup" action="<?= base_url() ?>api/users/signup">
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td>Name</td>
	  <td><input type="text" name="name" value="<?= set_value('name', $name) ?>"></td>
	</tr>
	<tr>
	  <td>Email</td>
	  <td>
	  	<input type="text" name="email" value="<?= set_value('email', $email) ?>">
	  	<div id="email_error"></div>
	  </td>
	</tr>
	<tr>  
	  <td>Password</td>
	  <td><input type="password" name="password" value="<?= set_value('password', $password) ?>"></td>
	</tr>
	<tr>  
	  <td>Confirm Password</td>
	  <td><input type="password" name="password_confirm" value="<?= set_value('password_confirm', $password_confirm) ?>"></td>
	</tr>
	<tr> 
	  <td colspan="2"><input type="submit" name="submit" value="Signup"></td>
	</tr>
</table>	
</form>

<?= $this->social_igniter->get_social_logins('<div class="social_login">', '</div>'); ?>

<script type="text/javascript">
$(document).ready(function()
{
	doPlaceholder('[name=name]', 'Joe Smith');
	doPlaceholder('[name=email]', 'your@email.com');
	doPlaceholder('[name=password]', 'password');
	doPlaceholder('[name=password_confirm]', 'password');

	$("#user_signup").bind("submit", function(eve)
	{	
		eve.preventDefault();
		
		var name				= isFieldValid('[name=name]', 'Joe Smith', 'Enter your name');
		var password			= isFieldValid('[name=password]', 'password', 'password123');
		var password_confirm	= isFieldValid('[name=password_confirm]', 'password', 'password123');
		var email				= isFieldValid('[name=email]', 'your@email.com', 'Enter your email address');
		var email_valid			= validateEmailAddress($('[name=email]').val());				
		
		// Is Valid		
		if (name == true && password == true && password_confirm == true && email == true && email_valid == true)
		{					
			var signup_data = $('#user_signup').serializeArray();
		
			$.ajax(
			{
				url			: base_url + 'api/users/create',
				type		: 'POST',
				dataType	: 'json',
				data		: signup_data,
		  		success		: function(result)
		  		{
					$('html, body').animate({scrollTop:0});
					
					if (result.status == 'success')
					{
						$('#content_message').notify({scroll:true,status:result.status,message:result.message + '. You will now be redirected to login',complete:'redirect',redirect:base_url + 'login'});

						$('[name=name]').val('');
						$('[name=email]').val('');
						$('[name=password]').val('');
						$('[name=password_confirm]').val('');
					}
					else
					{
						$('#content_message').notify({scroll:true,status:result.status,message:result.message});					
					}
			 	}
			});	
		}
		else if (email == true && email_valid == false)
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
<?php else: ?>

<p>Sorry user signup is currently turned off</p>

<?php endif; ?>