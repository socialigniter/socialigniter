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

function addNewDevice(result, device, device_token)
{
	console.log('inside addNewDevice');
	
	var device_new	= true;
	var user_data	= {
		"user_id":result.user.user_id,	 
		"consumer_key": result.user.consumer_key,
		"consumer_secret": result.user.consumer_secret,
		"token": result.user.token,
		"token_secret": result.user.token_secret
	}
	
	$.each(result.meta, function(key, meta) 
	{
		console.log(meta);

		if (meta.meta == 'device')
		{
			var this_device = JSON.parse(meta.value);

			if (this_device.device == device && this_device.device_token == device_token)
			{
				device_new = false;
			}
		}
	});

	if (device_new == true)
	{
		var device_json = {
			'device': device,
			'type': 'iPhone',
			'device_token': device_token
		}

//		var device_data = [];
//		device_data.push({'name':'device','value': JSON.stringify(device_json)});

		$.oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/users/device_create',
			type		: 'POST',
			dataType	: 'json',
			data		: [{'name':'device','value': JSON.stringify(device_json)}],
		  	success		: function(result)
		  	{
		  		console.log(result);
		  	}
		});	
	}
	
	
}

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
						console.log(result.meta);
	
						addNewDevice(result, '12123123asdasd23423', 'asdadas4352343s213a21das5d');							

			  		/*
						$('html, body').animate({scrollTop:0});
						if (result.status == 'success')
						{
							setTimeout(function() { window.location.href = base_url + 'home' });					
						}
						else
						{
							$('#content_message').notify({status:result.status,message:result.message});					
						}
					*/
				 	}
				});
			}
		});
	});
});
</script>