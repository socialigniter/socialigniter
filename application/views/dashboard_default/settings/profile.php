<form method="post" name="user_profile" id="user_profile" action="<?= base_url() ?>api/users/modify/id/<?= $logged_user_id ?>" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Picture</td>
		<td>
		<div id="profile_picture">
			<img id="profile_thumbnail" src="<?= $thumbnail ?>" border="0">
		</div>
		<div id="profile_picture_uploader">
		<ul id="profile_picture_change" class="item_actions_list">
			<li><a id="pickfiles" href="#"><span class="actions action_edit"></span> Upload A Picture</a></li>
		<?php if ($image): ?>
			<li><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<?php else: ?>
			<li class="small_details"><span class="actions_blank"></span> <?= config_item('users_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('users_images_formats'))) ?>)</li>			
		<?php endif; ?>
		</ul>
		</div>	
		</td>
	</tr>
	<tr>
		<td>Name</td>
		<td colspan="2"><input type="text" name="name" placeholder="Your Name" size="40" value="<?= $name ?>"></td>
	</tr>
    <tr>
		<td>Username</td>
		<td><input type="text" name="username" size="40" value="<?= set_value('username', $username) ?>"></td>
	</tr>
    <tr>
		<td>Email</td>
		<td><input type="email" name="email" size="40" value="<?= set_value('email', $email) ?>"></td>
	</tr>
	<tr>
		<td>Language</td>
		<td><?= form_dropdown('language', config_item('languages'), $language); ?></td>		
	</tr>
	<tr>
		<td>Timezone</td>
		<td><?= timezone_menu($time_zone); ?></td>
	</tr>
	<tr>
		<td>Geo</td>
		<td><input type="checkbox" name="geo_enabled" value="<?= $geo_enabled ?>"> Add my location to content & updates</td>
	</tr>		
  	<tr>
  		<td>Privacy</td>
  		<td><input type="checkbox" name="privacy" value="<?= $privacy ?>"> Keep my profile private</td>
  	</tr>
	</table>
	<p><input type="submit" value="Save"></p>
</form>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.flash.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.mediaUploader.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	// Profile Picture
	$(this).mediaUploader(
	{
		max_size	: '<?= $upload_size ?>mb',
		create_url	: base_url + 'api/users/upload_profile_picture/id/' + user_data.user_id,
		formats		: {title : 'Image Files', extensions : '<?= $upload_formats ?>'},
		start		: function(files)
		{
			// Hide / Replace Upload Link
			$('#profile_picture_container').replaceWith('<ul id="profile_picture_container" class="item_actions_list"><li><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li></ul>');
			$('#file_uploading_name').append(files[0].name);
		},
		complete	: function(response)
		{
			// Hide Upload
			$('#profile_picture_container').delay(750).fadeOut(function()
			{
				// Replace Uploading Status
				$('#profile_picture_uploader').append('<ul id="profile_picture_container" class="item_actions_list"><li><a id="pickfiles" href="#"><span class="actions action_edit"></span> Change Picture</a></li><li><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li></ul>');
				$('#profile_picture_container').delay(1250).fadeIn();
			});
		
			if (response.status == 'success')
			{
				$('#profile_thumbnail').attr('src', base_url + 'uploads/profiles/' + user_data.user_id + '/small_' + response.data)
			}
			else
			{
				console.log(response);
			
				$('#content_message').notify({status:response.status,message:response.message});	
			}		
		}
	});			
	
	// Delete Picture
	$('#delete_picture').live('click', function(eve)
	{	
		eve.preventDefault();
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/users/delete_profile_picture/id/' + user_data.user_id,
			type		: 'GET',
			dataType	: 'json',
	  		success		: function(result)
	  		{			
				if (result.status == 'success')
				{
					$('#profile_picture_container').fadeOut(function()
					{
						$(uploader_list).remove();
						$(uploader_parent).append(uploader_create);
						$(uploader_list).fadeIn('slow');
												
						$('#profile_thumbnail').attr('src', base_url + 'uploads/profiles/medium_nopicture.png');
					});
				}
				else
				{
					$('#content_message').notify({status:result.status,message:result.message});			
				}
		 	}
		});
	});		

	// Update Profile Data
	$("#user_profile").bind('submit', function(eve)
	{	
		eve.preventDefault();
		var profile_data = $('#user_profile').serializeArray();
	
		console.log(profile_data);
	
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
				$('#content_message').notify({status:result.status,message:result.message});
		 	}
		});		
	});	
});
</script>
