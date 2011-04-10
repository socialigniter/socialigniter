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

// Elements for Placeholder
var validation_rules = [{
	'element' 	: '[name=name]', 
	'holder'	: 'Joe Smith', 
	'message'	: 'Enter your name'
},{
	'element' 	: '[name=email]', 
	'holder'	: 'your@email.com', 
	'message'	: 'Enter your email'	
},{
	'element' 	: '[name=password]', 
	'holder'	: 'password', 
	'message'	: 'password123'	
},{
	'element' 	: '[name=password_confirm]', 
	'holder'	: 'password', 
	'message'	: 'password123'
}]

$(document).ready(function()
{
	// Placeholders
	makePlaceholders(validation_rules);

	$("#user_signup").bind('submit', function(eve)
	{	
		eve.preventDefault();	

		var email_valid	= validateEmailAddress($('[name=email]').val());					
		
		// Validation	
		if (validationRules(validation_rules) && email_valid == true)
		{
			// Strip Empty
			cleanAllFieldsEmpty(validation_rules);
							
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
						// Do Login
						$.ajax(
						{
							url			: base_url + 'api/users/login',
							type		: 'POST',
							dataType	: 'json',
							data		: signup_data,
					  		success		: function(result)
					  		{
					  			console.log('inside login');
					  		
								$('html, body').animate({scrollTop:0});
						
								$('[name=name]').val('');
								$('[name=email]').val('');
								$('[name=password]').val('');
								$('[name=password_confirm]').val('');						
								
								if (result.status == 'success')
								{
									$('#content_message').notify({scroll:true,status:result.status,message:result.message + '. You will now be logged in',complete:'redirect',redirect: base_url + 'home'});								
								}
								else
								{
									$('#content_message').notify({scroll:true,status:result.status,message:result.message});					
								}
						 	}
						});
					}
					else
					{
						$('#content_message').notify({scroll:true,status:result.status,message:result.message});					
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
<?php else: ?>

<p>Sorry user signup is currently turned off</p>

<?php endif; ?>