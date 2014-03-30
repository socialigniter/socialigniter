<form id="user_editor" method="post" action="<?= base_url()."api/users/create" ?>">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Level:</td>
		<td><?= form_dropdown('user_level_id', config_item('users_levels'), $user_level_id) ?></td>    	
	</tr>
	<tr>
		<td>Name:</td>
		<td>
			<input type="text" id="signup_name" name="name" value="<?= $name ?>">	  	
			<span id="signup_name_error"></span>
		</td>
	</tr>
	<tr>
		<td>Username:</td>
		<td><input type="text" name="username" id="signup_username" value="<?= $username ?>"></td>
	</tr>
	<tr>
		<td>Email:</td>
		<td>
			<input type="text" id="signup_email" name="email" value="<?= $email ?>">
			<span id="signup_email_error"></span>
		</td>
	</tr>
	<tr>
		<td>Password:</td>
		<td>
			<input type="text" id="signup_password" name="password" value="<?= $password ?>"> <a href="#">Email new password</a>
			<span id="signup_password_error"></span>
		</td>
	</tr>
	<tr>	
		<td>Confirm Password:</td>
		<td>
			<input type="password" id="signup_password_confirm" name="password_confirm" value="<?= $password ?>">
			<span id="signup_password_confirm_error"></span>
		</td>
	</tr>		
	<tr>		
		<td>Company:</td>
		<td><input type="text" name="company" value="<?= $company ?>"></td>
	</tr>
	<tr>		
		<td>Location:</td>
		<td><input type="text" name="location" value="<?= $location ?>"></td>
	</tr>
	<tr>
		<td>Website:</td>
		<td><input type="text" name="url" value="<?= $url ?>"></td>
	</tr>
	<tr>		
		<td>Bio:</td>
		<td><textarea name="bio" cols="30" rows="4"><?= $bio ?></textarea></td>
	</tr>
	</table>
	<p>Welcome Email
		<select name="welcome_email">
			<option value="no">No</option>
			<option value="yes">Yes</option>
		</select>
	</p>

	<input type="hidden" name="action" value="<?= $action ?>">
	<input type="submit" value="<?= $sub_title ?> User" />

</form>

<script type="text/javascript">
$(document).ready(function()
{
	// Slugify
	$('#signup_name').slugify({url: base_url + '/people/', slug:'#signup_username', name:'signup_username', slugValue:'<?= $username ?>'});

	// Create
	$("#user_editor").bind('submit', function(e)
	{
		e.preventDefault();	
		if ($('[name=action]').val() === 'create') {
			$.validator(
			{
				elements :		
					[{
						'selector' 	: '#signup_name', 
						'rule'		: 'require', 
						'field'		: 'Enter your name',
						'action'	: 'label'					
					},{
						'selector' 	: '#signup_email', 
						'rule'		: 'email', 
						'field'		: 'Please enter a valid email',
						'action'	: 'label'							
					},{
						'selector' 	: '#signup_password', 
						'rule'		: 'require', 
						'field'		: 'Please enter a password',
						'action'	: 'label'					
					},{
						'selector' 	: '#signup_password_confirm', 
						'rule'		: 'confirm', 
						'field'		: 'Please confirm your password',
						'action'	: 'label'					
					}],
				message : '',
				success	: function()
				{					
					$.oauthAjax(
					{
						oauth 		: user_data,
						url			: base_url + 'api/users/create',
						type		: 'POST',
						dataType	: 'json',
						data		: $('#user_editor').serializeArray(),
				  		success		: function(result)
				  		{
							$('html, body').animate({scrollTop:0});
							if (result.status == 'success')
							{							
								$('[name=name]').val('');
								$('[name=email]').val('');
								$('[name=password]').val('');
								$('[name=password_confirm]').val('');						
	
								$('#content_message').notify({status:result.status,message:result.message,complete:'redirect',redirect: base_url + 'home'});								
							}
							else
							{
								$('#content_message').notify({status:result.status,message:result.message});					
							}
					 	}
					});
				}
			});
		}
		else {
			$('#content_message').notify({status:'error',message:'Updating user data is not implemented yet'});
		}
		
	});	
});
</script>