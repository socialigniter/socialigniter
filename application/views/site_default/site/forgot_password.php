<h2>Forgot Password</h2>
<p>Please enter your email address</p>

<form method="post" name="forgot_password" id="forgot_password" action="<?= base_url()."api/users/password_forgot"; ?>">
	<p>
		<input type="text" name="email" id="forgot_email" placeholder="you@email.com" value="">
	  	<span id="forgot_email_error"></span>
	</p>
	<p id="email_error"></p>
	<p><input type="submit" name="submit" value="Retrieve" /></p>
</form>

<script type="text/javascript">
$(document).ready(function()
{			
	$("#forgot_password").bind('submit', function(e)
	{
		e.preventDefault();	
		$.validator(
		{
			elements :		
				[{
				'selector' 	: '#forgot_email', 
				'rule'		: 'email', 
				'field'		: 'Please enter a valid email',
				'action'	: 'label'	
				}],
			message : '',
			success	: function()
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
		});
	});
});
</script>