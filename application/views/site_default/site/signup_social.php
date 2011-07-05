<h2>Signup with <?= ucwords($signup_module) ?></h2>

<h3>Howdi <?= $name ?></h3>

<p>Your account is almost setup, please enter your email address</p>

<form method="post" name="user_signup" id="user_signup" action="<?= base_url() ?>api/users/set_userdata_signup_email">
	
	<p><input type="text" name="signup_email" value="<?= $signup_email ?>"></p>
	<div id="email_error"></div>
	
	<p><input type="submit" name="submit" value="Signup"></p>

</form>

<script type="text/javascript">
$(document).ready(function()
{
	doPlaceholder('[name=signup_email]', 'your@email.com');
	
	$("#user_signup").bind("submit", function(eve)
	{
		eve.preventDefault();

		var email			= isFieldValid('[name=signup_email]', 'your@email.com', 'Enter your email address');
		var email_valid		= validateEmailAddress($('[name=signup_email]').val());

		// Is Valid
		if (email == true && email_valid == true)
		{			
			var signup_data = $('#user_signup').serializeArray();

			$.ajax(
			{
				url			: base_url + 'api/users/set_userdata_signup_email',
				type		: 'POST',
				dataType	: 'json',
				data		: signup_data,
		  		success		: function(result)
		  		{
					$('html, body').animate({scrollTop:0});

					if (result.status == 'success')
					{
						$('#content_message').notify({status:result.status,message:result.message,complete:'redirect',redirect:'<?= $return_url ?>'});
					}
					else
					{
						$('#content_message').notify({status:result.status,message:result.message});					
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