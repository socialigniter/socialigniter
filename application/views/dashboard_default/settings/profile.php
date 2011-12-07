<form method="post" name="user_profile" id="user_profile" action="<?= base_url() ?>api/users/modify/id/<?= $logged_user_id ?>" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Picture</td>
		<td>
		<div id="profile_picture">
			<img id="profile_thumbnail" src="<?= $thumbnail ?>" border="0">
		</div>
		<ul id="profile_picture_upload" class="item_actions_list">
			<li id="uploading_pick"><a id="pick_image" href="#"><span class="actions action_upload"></span> Upload A Picture</a></li>
			<li id="uploading_status" class="hide"><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li>			
		<?php if ($image): ?>
			<li id="uploading_delete"><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
			<li id="uploading_details" class="small_details hide"><span class="actions_blank"></span> <?= config_item('users_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('users_images_formats'))) ?>)</li>
		<?php else: ?>
			<li id="uploading_delete" class="hide"><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
			<li id="uploading_details" class="small_details"><span class="actions_blank"></span> <?= config_item('users_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('users_images_formats'))) ?>)</li>			
		<?php endif; ?>
		</ul>
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
<script type="text/javascript" src="<?= base_url() ?>js/plupload.html5.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.flash.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	// Profile Picture
	$('#pick_image').mediaUploader(
	{
		max_size	: '<?= $upload_size ?>mb',
		create_url	: base_url + 'api/users/upload_profile_picture/id/' + user_data.user_id,
		formats		: {title : 'Allowed Files', extensions : '<?= $upload_formats ?>'},
		start		: function(files)
		{
			// Show Upload Link
			$('#uploading_pick').hide(); 
			$('#uploading_delete').hide();
			$('#uploading_details').hide();
			$('#uploading_status').show();
			$('#file_uploading_name').html(files[0].name);
		},
		complete	: function(response)
		{
			// Replace Uploading Status
			$('#uploading_status').delay(500).fadeOut();
			$('#uploading_pick').delay(1250).fadeIn(); 
			$('#uploading_delete').delay(1250).fadeIn();
	
			if (response.status == 'success')
			{
				$('#profile_thumbnail').attr('src', base_url + 'uploads/profiles/' + user_data.user_id + '/small_' + response.upload_info.file_name)
			}
			else
			{			
				$('#content_message').notify({status:response.status,message:response.message});	
			}		
		}
	});			
	
	// Delete Picture
	$('#delete_picture').live('click', function(e)
	{	
		e.preventDefault();
		$.oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/users/delete_profile_picture/id/' + user_data.user_id,
			type		: 'GET',
			dataType	: 'json',
	  		success		: function(result)
	  		{			
				if (result.status == 'success')
				{
					$('#profile_thumbnail').attr('src', base_url + 'uploads/profiles/medium_nopicture.png');				
					$('#uploading_delete').fadeOut('slow', function()
					{
						$('#uploading_details').delay(500).fadeIn();
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
	$("#user_profile").bind('submit', function(e)
	{	
		e.preventDefault();	
		$.oauthAjax(
		{
			oauth 		: user_data,
			url			: $(this).attr('ACTION'),
			type		: 'POST',
			dataType	: 'json',
			data		: $('#user_profile').serializeArray(),
	  		success		: function(result)
	  		{
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({status:result.status,message:result.message});
		 	}
		});		
	});	
});
</script>
