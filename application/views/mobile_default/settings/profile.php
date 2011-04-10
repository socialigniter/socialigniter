<form method="post" name="user_profile" id="user_profile" action="<?= base_url() ?>api/users/modify/id/<?= $logged_user_id ?>" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Picture:</td>
		<td><img src="<?= $thumbnail ?>" border="0"></td>
		<td><input type="file" size="20" name="userfile"></td>
	</tr>
	<?php if ($image != '') { ?>
	<tr>
		<td></td>
		<td colspan="2"><input type="checkbox" name="delete_pic" id="delete_pic" value="1"> Delete picture</td>
	</tr>
	<?php } ?>
	<tr>
		<td>Name:</td>
		<td colspan="2"><input type="text" name="name" placeholder="Your Name" size="40" value="<?= $name ?>"></td>
	</tr>
    <tr>
		<td>Username:</td>
		<td colspan="2"><input type="text" name="username" size="40" value="<?= set_value('username', $username) ?>"></td>
	</tr>
    <tr>
		<td>Email:</td>
		<td colspan="2"><input type="email" name="email" size="40" value="<?= set_value('email', $email) ?>"></td>
	</tr>
	<tr>
		<td>Language</td>
		<td colspan="2"><?= form_dropdown('language', config_item('languages'), $language); ?></td>		
	</tr>
	<tr>
		<td>Timezone</td>
		<td colspan="2"><?= timezone_menu($time_zone); ?></td>
	</tr>
	<tr>
		<td>Geo Enable:</td>
		<td colspan="2"><input type="checkbox" name="geo_enabled" value="<?= $geo_enabled ?>"> Add my location to content</td>
	</tr>		
  	<tr>
  		<td>Privacy:</td>
  		<td colspan="2"><input type="checkbox" name="privacy" value="<?= $privacy ?>"> Keep my feeds private</td>
  	</tr>
    <tr>		
		<td colspan="3"><input type="submit" value="Save" /></td>
  	</tr>  	
	</table>
</form>

<script type="text/javascript">
$(document).ready(function()
{
	$("#user_profile").bind("submit", function(e)
	{	
		e.preventDefault();
		var profile_data = $('#user_profile').serializeArray();
	
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: $(this).attr('ACTION'),
			type		: 'POST',
			dataType	: 'json',
			data		: profile_data,
	  		success		: function(result)
	  		{
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});
				
				if (result.status == 'success')
				{
					// Update Viewed User Data
					$('#logged_name').html(result.data.name);
					// DO PICTURE ONCE WORKING
				}
				
		 	}
		});		
	});	
});
</script>
