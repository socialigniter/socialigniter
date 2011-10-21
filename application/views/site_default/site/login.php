<h2>Login</h2>
<form method="post" name="user_login" id="user_login">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>Email</td>
  <td>
  	<input type="text" name="email" id="login_email" placeholder="you@email.com" value="">
  	<span id="login_email_error"></span>
  </td>
</tr>
<tr>  
  <td>Password</td>
  <td>
  	<input type="password" name="password" id="login_password" placeholder="********" value="">
  	<span id="login_password_error"></span>
  </td>
</tr>
<tr>
  <td>Remember</td> 
  <td><?= form_checkbox('remember', '1', TRUE, 'id="login_remember"');?> 
  <a href="<?= base_url() ?>forgot_password">Forgot password?</a>      
  </td>
</tr>    
<tr>
  <td colspan="2">
  	<input type="submit" name="submit" value="Login">
  </td>
</tr>
</table>
</form>
<?= $this->social_igniter->get_social_logins('<div class="social_login">', '</div>'); ?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#user_login').bind('submit', function(e)
	{	
		e.preventDefault();
		$.validator(
		{
			elements :
				[{
					'selector' 	: '#login_email', 
					'rule'		: 'email', 
					'field'		: 'Please enter a valid Email',
					'action'	: 'label'	
				},{
					'selector' 	: '#login_password', 
					'rule'		: 'require', 
					'field'		: 'Please enter your Password',
					'action'	: 'label'
				}],
			message : '',
			success	: function()
			{
				var login_data = $('#user_login').serializeArray();
				login_data.push({'name':'session','value':'1'});		
			
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
		});
	});
});
</script>