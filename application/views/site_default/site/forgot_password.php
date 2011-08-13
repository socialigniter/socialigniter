<h2>Forgot Password</h2>
<p>Please enter your email address</p>

<form method="post" name="forgot_password" id="forgot_password" action="<?= base_url()."api/users/password_forgot"; ?>">
	<p><input type="text" name="email" value=""></p>
	<p id="email_error"></p>
	<p><input type="submit" name="submit" value="Retrieve" /></p>
</form>

<script type="text/javascript">
// Elements for Placeholder
var validation_rules = [{
	'element' 	: '[name=email]', 
	'holder'	: 'your@email.com', 
	'message'	: 'Enter your email'	
}]

$(document).ready(function()
{
	// Placeholders
	makePlaceholders(validation_rules);

	$("#forgot_password").bind('submit', function(e)
	{
		e.preventDefault();
		var email_valid	= validateEmailAddress($('[name=email]').val());

		// Validation
		if (validationRules(validation_rules) && email_valid == true)
		{
			$.ajax(
			{
				url			: base_url + 'api/users/password_forgot',
				type		: 'POST',
				dataType	: 'json',
				data		: $('#forgot_password').serializeArray(),
		  		success		: function(result)
		  		{					
					if (result.status == 'success')
					{
						$('#content_message').notify({status:result.status,message:result.message,complete:'redirect',redirect: base_url + 'login'});					
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
	});
});
</script>